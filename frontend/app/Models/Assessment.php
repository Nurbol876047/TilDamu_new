<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Assessment extends Model
{
    public function create(array $payload): ?array
    {
        if (!$this->db()) {
            return null;
        }

        $stmt = $this->db()->prepare('INSERT INTO assessments (
            public_id, child_id, overall_score, diagnosis, confidence, transcription, ai_summary,
            strengths_json, recommendations_json, sounds_json, raw_payload_json, created_at
        ) VALUES (
            :public_id, :child_id, :overall_score, :diagnosis, :confidence, :transcription, :ai_summary,
            :strengths_json, :recommendations_json, :sounds_json, :raw_payload_json, NOW()
        )');

        $stmt->execute([
            'public_id' => $payload['public_id'],
            'child_id' => $payload['child_id'],
            'overall_score' => $payload['overall_score'],
            'diagnosis' => $payload['diagnosis'],
            'confidence' => $payload['confidence'],
            'transcription' => $payload['transcription'],
            'ai_summary' => $payload['ai_summary'],
            'strengths_json' => json_encode($payload['strengths'], JSON_UNESCAPED_UNICODE),
            'recommendations_json' => json_encode($payload['recommendations'], JSON_UNESCAPED_UNICODE),
            'sounds_json' => json_encode($payload['problematicSounds'], JSON_UNESCAPED_UNICODE),
            'raw_payload_json' => json_encode($payload['raw_payload'] ?? [], JSON_UNESCAPED_UNICODE),
        ]);

        return $this->findByPublicId($payload['public_id']);
    }

    public function findByPublicId(string $publicId): ?array
    {
        if (!$this->db()) {
            return null;
        }
        $stmt = $this->db()->prepare('SELECT a.*, c.full_name AS child_name, c.age AS child_age FROM assessments a LEFT JOIN children c ON c.id = a.child_id WHERE a.public_id = :public_id LIMIT 1');
        $stmt->execute(['public_id' => $publicId]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function latest(?int $childId = null): ?array
    {
        if (!$this->db()) {
            return null;
        }
        if ($childId) {
            $stmt = $this->db()->prepare('SELECT a.*, c.full_name AS child_name, c.age AS child_age FROM assessments a LEFT JOIN children c ON c.id = a.child_id WHERE a.child_id = :child_id ORDER BY a.created_at DESC LIMIT 1');
            $stmt->execute(['child_id' => $childId]);
        } else {
            $stmt = $this->db()->query('SELECT a.*, c.full_name AS child_name, c.age AS child_age FROM assessments a LEFT JOIN children c ON c.id = a.child_id ORDER BY a.created_at DESC LIMIT 1');
        }
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function history(?int $childId = null): array
    {
        if (!$this->db()) {
            return [];
        }
        if ($childId) {
            $stmt = $this->db()->prepare('SELECT public_id, overall_score, created_at FROM assessments WHERE child_id = :child_id ORDER BY created_at DESC LIMIT 10');
            $stmt->execute(['child_id' => $childId]);
        } else {
            $stmt = $this->db()->query('SELECT public_id, overall_score, created_at FROM assessments ORDER BY created_at DESC LIMIT 10');
        }
        return $stmt->fetchAll() ?: [];
    }

    public function byChildSummary(): array
    {
        if (!$this->db()) {
            return [];
        }
        $sql = 'SELECT c.id, c.full_name AS name, c.age, c.status, c.notes, a.diagnosis, a.overall_score AS progress, a.created_at AS last_session, a.sounds_json
                FROM children c
                LEFT JOIN assessments a ON a.id = (
                    SELECT a2.id FROM assessments a2 WHERE a2.child_id = c.id ORDER BY a2.created_at DESC LIMIT 1
                )
                ORDER BY c.created_at DESC';
        return $this->db()->query($sql)->fetchAll() ?: [];
    }

    public function analytics(): array
    {
        if (!$this->db()) {
            return ['soundCounts' => [], 'diagnosisCounts' => []];
        }

        $rows = $this->db()->query('SELECT diagnosis, sounds_json FROM assessments')->fetchAll() ?: [];
        $soundCounts = [];
        $diagnosisCounts = [];
        foreach ($rows as $row) {
            $diagnosis = $row['diagnosis'] ?: 'Другое';
            $diagnosisCounts[$diagnosis] = ($diagnosisCounts[$diagnosis] ?? 0) + 1;
            $sounds = json_decode((string) $row['sounds_json'], true) ?: [];
            foreach ($sounds as $sound) {
                $key = $sound['sound'] ?? '—';
                $soundCounts[$key] = ($soundCounts[$key] ?? 0) + 1;
            }
        }

        arsort($soundCounts);
        arsort($diagnosisCounts);
        return ['soundCounts' => $soundCounts, 'diagnosisCounts' => $diagnosisCounts];
    }

    /**
     * Real platform-wide statistics for the home page and dashboards.
     */
    public function platformStats(): array
    {
        if (!$this->db()) {
            return ['totalChildren' => 0, 'totalAssessments' => 0, 'avgScore' => 0, 'successRate' => 0];
        }

        $children = (int) ($this->db()->query('SELECT COUNT(*) AS cnt FROM children')->fetch()['cnt'] ?? 0);
        $assessments = (int) ($this->db()->query('SELECT COUNT(*) AS cnt FROM assessments')->fetch()['cnt'] ?? 0);
        $avgRow = $this->db()->query('SELECT AVG(overall_score) AS avg_score FROM assessments WHERE overall_score > 0')->fetch();
        $avgScore = (int) round((float) ($avgRow['avg_score'] ?? 0));
        $highScoreCount = (int) ($this->db()->query('SELECT COUNT(*) AS cnt FROM assessments WHERE overall_score >= 70')->fetch()['cnt'] ?? 0);
        $successRate = $assessments > 0 ? (int) round(($highScoreCount / $assessments) * 100) : 0;

        return [
            'totalChildren' => $children,
            'totalAssessments' => $assessments,
            'avgScore' => $avgScore,
            'successRate' => $successRate,
        ];
    }

    /**
     * Get assessment history for a specific child (for therapist detail view).
     */
    public function childHistory(int $childId, int $limit = 10): array
    {
        if (!$this->db()) {
            return [];
        }
        $stmt = $this->db()->prepare('SELECT public_id, overall_score, diagnosis, transcription, created_at FROM assessments WHERE child_id = :child_id ORDER BY created_at DESC LIMIT :lim');
        $stmt->bindValue(':child_id', $childId, \PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Progress over time for a child (for charts).
     */
    public function progressSeries(int $childId, int $limit = 8): array
    {
        if (!$this->db()) {
            return [];
        }
        $stmt = $this->db()->prepare('SELECT overall_score, DATE_FORMAT(created_at, "%d.%m") AS label FROM assessments WHERE child_id = :child_id ORDER BY created_at ASC LIMIT :lim');
        $stmt->bindValue(':child_id', $childId, \PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    private function hydrate(array $row): array
    {
        $row['strengths'] = json_decode((string) ($row['strengths_json'] ?? '[]'), true) ?: [];
        $row['recommendations'] = json_decode((string) ($row['recommendations_json'] ?? '[]'), true) ?: [];
        $row['problematicSounds'] = json_decode((string) ($row['sounds_json'] ?? '[]'), true) ?: [];
        return $row;
    }
}
