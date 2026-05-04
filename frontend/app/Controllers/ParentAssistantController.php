<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Assessment;
use App\Services\ParentChatService;

final class ParentAssistantController extends Controller
{
    public function send(Request $request): never
    {
        $message = trim((string) $request->input('message', ''));
        if ($message === '') {
            $this->json(['ok' => false, 'message' => tr('chat.empty')], 422);
        }

        $assessmentId = (string) ($request->input('assessment_id', '') ?: ($_SESSION['last_assessment_public_id'] ?? ''));
        $assessment = $assessmentId !== '' ? (new Assessment())->findByPublicId($assessmentId) : null;
        $context = [
            'childName' => $assessment['child_name'] ?? tr('common.child'),
            'diagnosis' => $assessment['diagnosis'] ?? '',
            'score' => $assessment['overall_score'] ?? '',
            'problemSounds' => $assessment['problematicSounds'] ?? [],
        ];
        try {
            $response = (new ParentChatService())->reply($message, $context, $assessment['child_id'] ?? null);
            $this->json(['ok' => true, 'reply' => $response['reply']]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'message' => $e->getMessage()], 502);
        }
    }
}
