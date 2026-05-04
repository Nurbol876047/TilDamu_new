<?php

declare(strict_types=1);

namespace App\Services;

final class AvatarService
{
    public function validate(?array $file): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return tr('auth.avatar_upload_error');
        }

        if ((int) ($file['size'] ?? 0) > 5 * 1024 * 1024) {
            return tr('auth.avatar_size_error');
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];

        $mime = (string) mime_content_type((string) ($file['tmp_name'] ?? ''));
        if (!isset($allowed[$mime])) {
            return tr('auth.avatar_type_error');
        }

        return null;
    }

    public function store(?array $file): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];
        $mime = (string) mime_content_type((string) ($file['tmp_name'] ?? ''));
        $extension = $allowed[$mime] ?? null;
        if ($extension === null) {
            return null;
        }

        $relativeDir = 'storage/uploads/avatars';
        $targetDir = base_path($relativeDir);
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }

        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            return null;
        }

        $filename = 'avatar-' . date('Ymd-His') . '-' . bin2hex(random_bytes(6)) . '.' . $extension;
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $filename;

        if (!@move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
            return null;
        }

        return '/' . trim($relativeDir, '/') . '/' . $filename;
    }
}
