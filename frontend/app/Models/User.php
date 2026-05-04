<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class User extends Model
{
    public function register(array $data): ?array
    {
        if (!$this->db()) {
            return null;
        }

        $stmt = $this->db()->prepare(
            'INSERT INTO users (full_name, email, phone, password_hash, role, avatar_url, created_at)
             VALUES (:full_name, :email, :phone, :password_hash, :role, :avatar_url, NOW())'
        );

        $stmt->execute([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password_hash' => $data['password'],
            'role' => $data['role'] ?? 'parent',
            'avatar_url' => $data['avatar_url'] ?? null,
        ]);

        $userId = (int) $this->db()->lastInsertId();

        if (($data['role'] ?? 'parent') === 'parent' && !empty($data['child_name'])) {
            $childAge = isset($data['child_age']) && is_numeric($data['child_age']) ? (int) $data['child_age'] : null;
            $child = (new Child())->firstOrCreate($data['child_name'], $childAge);

            if ($child && isset($child['id'])) {
                $this->db()->prepare('UPDATE users SET child_id = :child_id WHERE id = :id')
                    ->execute([
                        'child_id' => $child['id'],
                        'id' => $userId,
                    ]);
            }
        }

        return $this->findById($userId);
    }

    public function attempt(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!(int) ($user['is_active'] ?? 0)) {
            return null;
        }

        if ((string) $password !== (string) ($user['password_hash'] ?? '')) {
            return null;
        }

        $this->db()?->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')
            ->execute(['id' => $user['id']]);

        return $user;
    }

    public function findById(int $id): ?array
    {
        if (!$this->db()) {
            return null;
        }

        $stmt = $this->db()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        if (!$this->db()) {
            return null;
        }

        $stmt = $this->db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        return $stmt->fetch() ?: null;
    }

    public function emailExists(string $email): bool
    {
        if (!$this->db()) {
            return false;
        }

        $stmt = $this->db()->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);

        return ((int) ($stmt->fetch()['cnt'] ?? 0)) > 0;
    }

    public function updateAvatar(int $id, string $avatarUrl): void
    {
        if (!$this->db()) {
            return;
        }

        $stmt = $this->db()->prepare('UPDATE users SET avatar_url = :avatar_url WHERE id = :id');
        $stmt->execute([
            'avatar_url' => $avatarUrl,
            'id' => $id,
        ]);
    }

    public function updateProfile(int $id, array $data): void
    {
        if (!$this->db()) {
            return;
        }

        $fields = [];
        $params = ['id' => $id];

        if (isset($data['full_name'])) {
            $fields[] = 'full_name = :full_name';
            $params['full_name'] = $data['full_name'];
        }

        if (isset($data['phone'])) {
            $fields[] = 'phone = :phone';
            $params['phone'] = $data['phone'];
        }

        if (!empty($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = $data['password'];
        }

        if (isset($data['avatar_url'])) {
            $fields[] = 'avatar_url = :avatar_url';
            $params['avatar_url'] = $data['avatar_url'];
        }

        if ($fields !== []) {
            $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
            $this->db()->prepare($sql)->execute($params);
        }
    }
}
