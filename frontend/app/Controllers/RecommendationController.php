<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Assessment;
use App\Models\Exercise;
use App\Services\RecommendationService;

final class RecommendationController extends Controller
{
    public function index(Request $request): void
    {
        $assessment = null;
        $assessmentId = (string) ($request->query('assessment') ?? ($_SESSION['last_assessment_public_id'] ?? ''));
        if ($assessmentId !== '') {
            $assessment = (new Assessment())->findByPublicId($assessmentId);
        }
        $data = (new RecommendationService())->data($assessment);
        $stats = (new Exercise())->stats($assessment['child_id'] ?? null);
        $this->view('pages.recommendations', [
            'pageTitle' => (string) t('recommendations'),
            'exercises' => $data['exercises'],
            'videos' => $data['videos'],
            'achievements' => $data['achievements'],
            'exerciseStats' => $stats,
            'assessmentContext' => [
                'childName' => $assessment['child_name'] ?? 'Ребенок',
                'diagnosis' => $assessment['diagnosis'] ?? '',
                'score' => $assessment['overall_score'] ?? '',
                'problemSounds' => $assessment['problematicSounds'] ?? [],
                'childId' => $assessment['child_id'] ?? null,
                'publicId' => $assessment['public_id'] ?? null,
            ],
        ]);
    }

    public function completeExercise(Request $request): never
    {
        $exerciseId = (int) $request->input('exercise_id', 0);
        $childId = $request->input('child_id');
        $stars = (int) $request->input('stars', 0);
        (new Exercise())->complete($exerciseId, is_numeric($childId) ? (int) $childId : null, $stars);
        $stats = (new Exercise())->stats(is_numeric($childId) ? (int) $childId : null);
        $this->json(['ok' => true, 'stats' => $stats]);
    }
}
