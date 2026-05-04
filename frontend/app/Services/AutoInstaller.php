<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

final class AutoInstaller
{
    public function run(): void
    {
        if (!env('DB_AUTO_MIGRATE', true)) {
            return;
        }

        $pdo = Database::connection();
        if (!$pdo) {
            return;
        }

        $lockFile = storage_path('cache/install.lock');
        if (is_file($lockFile)) {
            return;
        }

        $schemaFile = base_path('app/Database/schema.sql');
        if (is_file($schemaFile)) {
            $sql = file_get_contents($schemaFile) ?: '';
            if ($sql !== '') {
                $pdo->exec($sql);
            }
        }

        // Users table migration
        $usersMigration = base_path('app/Database/users_migration.sql');
        if (is_file($usersMigration)) {
            $sql = file_get_contents($usersMigration) ?: '';
            if ($sql !== '') {
                $pdo->exec($sql);
            }
        }

        // Courses tables migration
        $coursesMigration = base_path('app/Database/courses_migration.sql');
        if (is_file($coursesMigration)) {
            $sql = file_get_contents($coursesMigration) ?: '';
            if ($sql !== '') {
                $pdo->exec($sql);
            }
        }

        if (env('DB_AUTO_SEED', true)) {
            (new SeederService())->seed();
        }

        if (!is_dir(dirname($lockFile))) {
            @mkdir(dirname($lockFile), 0775, true);
        }
        @file_put_contents($lockFile, 'installed at ' . date('c'));
    }
}
