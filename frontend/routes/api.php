<?php

declare(strict_types=1);

use App\Controllers\DiagnosisController;
use App\Controllers\ParentAssistantController;
use App\Controllers\RecommendationController;
use App\Controllers\TherapistController;

/**
 * Регистрирует канонический API-роут и его legacy-алиас с .php,
 * чтобы фронтенд и старые прямые вызовы не ломались.
 */
$apiPost = static function (string $path, callable|array $handler): void {
    app()->router()->post($path, $handler);
    app()->router()->post($path . '.php', $handler);
};

$apiPost('/api/diagnosis/start', [DiagnosisController::class, 'start']);
$apiPost('/api/diagnosis/analyze', [DiagnosisController::class, 'analyze']);
$apiPost('/api/diagnosis/complete', [DiagnosisController::class, 'complete']);
$apiPost('/api/exercises/complete', [RecommendationController::class, 'completeExercise']);
$apiPost('/api/parent-assistant/send', [ParentAssistantController::class, 'send']);
$apiPost('/api/children/notes', [TherapistController::class, 'saveNotes']);
