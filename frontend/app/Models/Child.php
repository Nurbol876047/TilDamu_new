<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Child extends Model
{
    public function all(): array
    {
        if (!$this->db()) {
            return [];
        }

        return $this->db()->query('SELECT * FROM children ORDER BY created_at DESC')->fetchAll() ?: [];
    }

    public function find(int $id): ?array
    {
        if (!$this->db()) {
            return null;
        }

        $stmt = $this->db()->prepare('SELECT * FROM children WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function firstOrCreate(string $name, ?int $age = null): ?array
    {
        if (!$this->db()) {
            return null;
        }

        $stmt = $this->db()->prepare('SELECT * FROM children WHERE full_name = :name AND (age = :age OR :age IS NULL) ORDER BY id DESC LIMIT 1');
        $stmt->execute(['name' => $name, 'age' => $age]);
        $existing = $stmt->fetch();
        if ($existing) {
            return $existing;
        }

        $insert = $this->db()->prepare('INSERT INTO children (full_name, age, status) VALUES (:name, :age, :status)');
        $insert->execute(['name' => $name, 'age' => $age, 'status' => 'Активен']);
        return $this->find((int) $this->db()->lastInsertId());
    }

    public function updateNotes(int $id, string $notes): void
    {
        if (!$this->db()) {
            return;
        }
        $stmt = $this->db()->prepare('UPDATE children SET notes = :notes WHERE id = :id');
        $stmt->execute(['notes' => $notes, 'id' => $id]);
    }

    public function totalChatMessages(): int
    {
        if (!$this->db()) {
            return 0;
        }
        return (int) ($this->db()->query('SELECT COUNT(*) AS cnt FROM chat_messages WHERE role = \'user\'')->fetch()['cnt'] ?? 0);
    }
}
