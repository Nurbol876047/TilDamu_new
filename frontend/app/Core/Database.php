<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): ?PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $driver = (string) env('DB_DRIVER', 'mysql');
        $host = (string) env('DB_HOST', '127.0.0.1');
        $port = (string) env('DB_PORT', '3306');
        $database = (string) env('DB_DATABASE', '');
        $user = (string) env('DB_USERNAME', '');
        $password = (string) env('DB_PASSWORD', '');
        $charset = (string) env('DB_CHARSET', 'utf8mb4');

        if ($database === '' || $user === '') {
            return null;
        }

        $dsn = match ($driver) {
            'mysql' => sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s', $driver, $host, $port, $database, $charset),
            default => throw new \RuntimeException('Unsupported database driver: ' . $driver),
        };

        try {
            self::$pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            self::log('Database connection failed: ' . $e->getMessage());
            return null;
        }

        return self::$pdo;
    }

    public static function log(string $message): void
    {
        $dir = storage_path('logs');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        @file_put_contents($dir . '/app.log', '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }
}
