<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Assessment;

final class ResultController extends Controller
{
    public function show(Request $request): void
    {
        $assessmentId = (string) ($request->query('assessment') ?? ($_SESSION['last_assessment_public_id'] ?? ''));
        $assessmentModel = new Assessment();
        $assessment = $assessmentId !== '' ? $assessmentModel->findByPublicId($assessmentId) : null;
        $assessment ??= $assessmentModel->latest();

        $report = null;
        $history = [];

        if ($assessment) {
            $report = [
                'diagnosisDate' => date('d.m.Y', strtotime((string) $assessment['created_at'])),
                'childName' => $assessment['child_name'] ?? 'Ребенок',
                'age' => $assessment['child_age'] ?? null,
                'overallScore' => (int) ($assessment['overall_score'] ?? 0),
                'diagnosis' => $assessment['diagnosis'] ?? '',
                'confidence' => (int) ($assessment['confidence'] ?? 0),
                'problematicSounds' => $assessment['problematicSounds'] ?? [],
                'strengths' => $assessment['strengths'] ?? [],
                'recommendations' => $assessment['recommendations'] ?? [],
                'summary' => $assessment['ai_summary'] ?? '',
                'publicId' => $assessment['public_id'] ?? null,
            ];

            $history = array_map(function (array $item) use ($report) {
                return [
                    'date' => date('d.m.Y', strtotime((string) $item['created_at'])),
                    'score' => (int) ($item['overall_score'] ?? 0),
                    'active' => ($item['public_id'] ?? '') === ($report['publicId'] ?? ''),
                    'public_id' => $item['public_id'] ?? '',
                ];
            }, $assessmentModel->history($assessment['child_id'] ?? null));
        }

        $this->view('pages.results', [
            'pageTitle' => (string) t('results'),
            'report' => $report,
            'history' => $history,
        ]);
    }
}
