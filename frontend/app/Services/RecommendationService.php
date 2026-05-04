<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Exercise;

final class RecommendationService
{
    public function data(?array $assessment = null): array
    {
        $exerciseModel = new Exercise();
        $exercises = $exerciseModel->all();
        $videos = [];
        foreach ($exercises as $exercise) {
            $videos[] = [
                'id' => $exercise['id'],
                'title' => $exercise['title'],
                'duration' => sprintf('%d:00', (int) ($exercise['duration_minutes'] ?? 5)),
                'url' => $exercise['video_url'] ?: '#',
            ];
        }

        $childId = $assessment['child_id'] ?? null;
        $stats = $exerciseModel->stats($childId);
        $completed = (int) ($stats['completed'] ?? 0);
        $totalExercises = count($exercises);
        $isKk = current_language() === 'kk';

        $achievements = [
            [
                'title' => $isKk ? 'Алғашқы жұлдыз' : 'Первая звезда',
                'desc' => $isKk ? 'Бірінші жаттығу орындалды' : 'Выполнено первое упражнение',
                'icon' => 'star',
                'gradient' => 'gradient-icon-orange',
                'bgGradient' => 'from-[#FFF9F0] to-white',
                'unlocked' => $completed >= 1,
            ],
            [
                'title' => $isKk ? 'Жылдам бастау' : 'Быстрый старт',
                'desc' => $isKk ? '3 жаттығу орындалды' : '3 упражнения выполнено',
                'icon' => 'bolt',
                'gradient' => 'gradient-icon-blue',
                'bgGradient' => 'from-[#F0F9FF] to-white',
                'unlocked' => $completed >= 3,
            ],
            [
                'title' => $isKk ? 'Ынталы оқушы' : 'Усердный ученик',
                'desc' => $isKk ? '5 жаттығу орындалды' : '5 упражнений выполнено',
                'icon' => 'trophy',
                'gradient' => 'gradient-icon-green',
                'bgGradient' => 'from-[#F0FDF4] to-white',
                'unlocked' => $completed >= 5,
            ],
            [
                'title' => $isKk ? 'Сөйлеу шебері' : 'Мастер речи',
                'desc' => $isKk ? 'Барлық жаттығулар орындалды' : 'Все упражнения выполнены',
                'icon' => 'medal',
                'gradient' => 'gradient-icon-purple',
                'bgGradient' => 'from-[#FAF5FF] to-white',
                'unlocked' => $totalExercises > 0 && $completed >= $totalExercises,
            ],
        ];

        return compact('exercises', 'videos', 'achievements');
    }
}
