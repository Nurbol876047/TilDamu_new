<?php

declare(strict_types=1);

namespace App\Services;

final class DiagnosisSessionService
{
    public function words(): array
    {
        return [
            ['ru' => 'мама', 'kk' => 'ана', 'en' => 'mama'],
            ['ru' => 'солнце', 'kk' => 'күн', 'en' => 'sun'],
            ['ru' => 'рыба', 'kk' => 'балық', 'en' => 'fish'],
            ['ru' => 'цветок', 'kk' => 'гүл', 'en' => 'flower'],
            ['ru' => 'шарик', 'kk' => 'шар', 'en' => 'ball'],
            ['ru' => 'лампа', 'kk' => 'шам', 'en' => 'lamp'],
            ['ru' => 'жук', 'kk' => 'қоңыз', 'en' => 'bug'],
            ['ru' => 'чашка', 'kk' => 'кесе', 'en' => 'cup'],
        ];
    }

    public function start(?string $childName, ?int $childAge, ?string $childDisorderType = null): void
    {
        $_SESSION['diagnosis'] = [
            'child_name' => $childName ?: 'Ребенок',
            'child_age' => $childAge,
            'child_disorder_type' => $childDisorderType,
            'results' => [],
            'started_at' => date('c'),
        ];
    }

    public function addWordResult(array $result): void
    {
        $_SESSION['diagnosis']['results'][] = $result;
    }

    public function get(): array
    {
        return $_SESSION['diagnosis'] ?? [
            'child_name' => 'Ребенок',
            'child_age' => null,
            'child_disorder_type' => null,
            'results' => []
        ];
    }

    public function clear(): void
    {
        unset($_SESSION['diagnosis']);
    }
}
