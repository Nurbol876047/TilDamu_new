<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class ChatMessage extends Model
{
    public function create(?int $childId, string $sessionId, string $role, string $message): void
    {
        if (!$this->db()) {
            return;
        }

        try {
            $stmt = $this->db()->prepare('INSERT INTO chat_messages (child_id, session_id, role, message, created_at) VALUES (:child_id, :session_id, :role, :message, NOW())');
            $stmt->execute([
                'child_id' => $childId,
                'session_id' => $sessionId,
                'role' => $role,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
        }
    }

    public function history(string $sessionId, int $limit = 12): array
    {
        if (!$this->db()) {
            return [];
        }

        try {
            $stmt = $this->db()->prepare('SELECT role, message, created_at FROM chat_messages WHERE session_id = :session_id ORDER BY id DESC LIMIT :limit');
            $stmt->bindValue(':session_id', $sessionId);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            return array_reverse($stmt->fetchAll() ?: []);
        } catch (\Throwable $e) {
            return [];
        }
    }
}
