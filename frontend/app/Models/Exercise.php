<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Exercise extends Model
{
    public function all(): array
    {
        if (!$this->db()) {
            return [];
        }
        return $this->db()->query('SELECT * FROM exercise_templates ORDER BY sort_order ASC, id ASC')->fetchAll() ?: [];
    }

    public function complete(int $exerciseId, ?int $childId, int $stars): void
    {
        if (!$this->db()) {
            return;
        }
        $stmt = $this->db()->prepare('INSERT INTO exercise_progress (child_id, exercise_template_id, stars_earned, completed_at) VALUES (:child_id, :exercise_template_id, :stars_earned, NOW())');
        $stmt->execute([
            'child_id' => $childId,
            'exercise_template_id' => $exerciseId,
            'stars_earned' => $stars,
        ]);
    }

    public function stats(?int $childId = null): array
    {
        if (!$this->db()) {
            return ['completed' => 0, 'stars' => 0];
        }
        if ($childId) {
            $stmt = $this->db()->prepare('SELECT COUNT(*) AS completed, COALESCE(SUM(stars_earned),0) AS stars FROM exercise_progress WHERE child_id = :child_id');
            $stmt->execute(['child_id' => $childId]);
            return $stmt->fetch() ?: ['completed' => 0, 'stars' => 0];
        }
        return $this->db()->query('SELECT COUNT(*) AS completed, COALESCE(SUM(stars_earned),0) AS stars FROM exercise_progress')->fetch() ?: ['completed' => 0, 'stars' => 0];
    }
}
