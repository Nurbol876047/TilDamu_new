<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Course extends Model
{
    public function all(bool $publishedOnly = false): array
    {
        if (!$this->db()) return [];
        $sql = 'SELECT c.*, u.full_name AS author_name FROM courses c LEFT JOIN users u ON u.id = c.author_id';
        if ($publishedOnly) $sql .= ' WHERE c.is_published = 1';
        $sql .= ' ORDER BY c.created_at DESC';
        return $this->db()->query($sql)->fetchAll() ?: [];
    }

    public function find(int $id): ?array
    {
        if (!$this->db()) return null;
        $stmt = $this->db()->prepare('SELECT c.*, u.full_name AS author_name FROM courses c LEFT JOIN users u ON u.id = c.author_id WHERE c.id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): ?int
    {
        if (!$this->db()) return null;
        $stmt = $this->db()->prepare('INSERT INTO courses (title, description, content, target_sounds, age_from, age_to, difficulty, lessons_count, is_published, author_id) VALUES (:title, :description, :content, :target_sounds, :age_from, :age_to, :difficulty, :lessons_count, :is_published, :author_id)');
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'content' => $data['content'] ?? '',
            'target_sounds' => $data['target_sounds'] ?? '',
            'age_from' => (int) ($data['age_from'] ?? 3),
            'age_to' => (int) ($data['age_to'] ?? 10),
            'difficulty' => $data['difficulty'] ?? 'Легко',
            'lessons_count' => (int) ($data['lessons_count'] ?? 0),
            'is_published' => (int) ($data['is_published'] ?? 1),
            'author_id' => $data['author_id'] ?? null,
        ]);
        return (int) $this->db()->lastInsertId();
    }

    public function addMedia(int $courseId, array $fileData): ?int
    {
        if (!$this->db()) return null;
        $stmt = $this->db()->prepare('INSERT INTO course_media (course_id, file_name, file_path, file_type, file_size, mime_type, sort_order) VALUES (:course_id, :file_name, :file_path, :file_type, :file_size, :mime_type, :sort_order)');
        $stmt->execute([
            'course_id' => $courseId,
            'file_name' => $fileData['file_name'],
            'file_path' => $fileData['file_path'],
            'file_type' => $fileData['file_type'],
            'file_size' => $fileData['file_size'] ?? 0,
            'mime_type' => $fileData['mime_type'] ?? '',
            'sort_order' => $fileData['sort_order'] ?? 0,
        ]);
        return (int) $this->db()->lastInsertId();
    }

    public function getMedia(int $courseId): array
    {
        if (!$this->db()) return [];
        $stmt = $this->db()->prepare('SELECT * FROM course_media WHERE course_id = :course_id ORDER BY sort_order, id');
        $stmt->execute(['course_id' => $courseId]);
        return $stmt->fetchAll() ?: [];
    }

    public function totalCount(): int
    {
        if (!$this->db()) return 0;
        return (int) ($this->db()->query('SELECT COUNT(*) AS cnt FROM courses')->fetch()['cnt'] ?? 0);
    }

    public static function detectFileType(string $mime): string
    {
        if (str_starts_with($mime, 'image/')) return 'image';
        if (str_starts_with($mime, 'audio/')) return 'audio';
        if (str_starts_with($mime, 'video/')) return 'video';
        return 'document';
    }
}
