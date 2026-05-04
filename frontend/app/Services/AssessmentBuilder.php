<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Assessment;
use App\Models\Child;

final class AssessmentBuilder
{
    public function finalizeSession(): array
    {
        $session = (new DiagnosisSessionService())->get();
        $results = $session['results'] ?? [];
        if ($results === []) {
            throw new \RuntimeException('Нет подтверждённых AI-результатов для формирования отчёта.');
        }

        $score = (int) round(array_sum(array_column($results, 'score')) / max(1, count($results)));
        $confidence = (int) round(array_sum(array_column($results, 'confidence')) / max(1, count($results)));
        $problemMap = [];
        $strengths = [];
        $recommendations = [];
        $transcriptions = [];
        foreach ($results as $item) {
            foreach (($item['problem_sounds'] ?? []) as $sound) {
                $key = $sound['sound'];
                if (!isset($problemMap[$key])) {
                    $problemMap[$key] = $sound;
                } else {
                    $problemMap[$key]['correct'] = (int) round(($problemMap[$key]['correct'] + ($sound['correct'] ?? 0)) / 2);
                }
            }
            $strengths = array_merge($strengths, $item['strengths'] ?? []);
            $recommendations = array_merge($recommendations, $item['recommendations'] ?? []);
            $transcriptions[] = $item['transcript'] ?? '';
        }

        $problematicSounds = array_values($problemMap);
        usort($problematicSounds, fn($a, $b) => ($a['correct'] ?? 0) <=> ($b['correct'] ?? 0));

        $diagnosis = $score >= 80
            ? 'Возрастная артикуляционная норма с зонами роста'
            : ($score >= 65 ? 'Функциональная дислалия (легкая форма)' : 'Функциональная дислалия (требует внимания логопеда)');

        $payload = [
            'public_id' => 'res-' . bin2hex(random_bytes(6)),
            'child_id' => null,
            'overall_score' => $score,
            'diagnosis' => $diagnosis,
            'confidence' => $confidence,
            'transcription' => trim(implode(', ', array_filter($transcriptions))),
            'ai_summary' => trim(implode(' ', array_values(array_unique(array_filter(array_map(fn($item) => (string) ($item['summary'] ?? ''), $results)))))) ?: 'Сформирован сводный отчёт по серии слов на основе ответов AI.',
            'strengths' => array_values(array_unique(array_slice($strengths, 0, 5))),
            'recommendations' => array_values(array_unique(array_slice($recommendations, 0, 5))),
            'problematicSounds' => array_slice($problematicSounds, 0, 5),
            'raw_payload' => ['session' => $session, 'aggregated_at' => date('c')],
        ];

        $childRecord = (new Child())->firstOrCreate((string) ($session['child_name'] ?? 'Ребенок'), isset($session['child_age']) ? (int) $session['child_age'] : null);
        if ($childRecord && isset($childRecord['id'])) {
            $payload['child_id'] = $childRecord['id'];
        }

        $saved = (new Assessment())->create($payload);
        $payload['child_name'] = $childRecord['full_name'] ?? ($session['child_name'] ?? 'Ребенок');
        $payload['child_age'] = $childRecord['age'] ?? ($session['child_age'] ?? null);

        (new DiagnosisSessionService())->clear();
        $_SESSION['last_assessment_public_id'] = $payload['public_id'];

        return $saved ?: $payload;
    }

}
