<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\AutoInstaller;
use DateTimeZone;

final class Application
{
    private static ?self $instance = null;
    private Router $router;
    private array $config = [];

    private function __construct()
    {
        self::$instance = $this;
        $this->router = new Router();
        $this->config = [
            'app.name' => env('APP_NAME', 'TilDamu.kz'),
            'app.url' => env('APP_URL', ''),
            'app.timezone' => env('APP_TIMEZONE', 'Asia/Almaty'),
            'app.locale' => env('APP_LOCALE', 'ru'),
            'app.fallback_locale' => env('APP_FALLBACK_LOCALE', 'kk'),
        ];

        date_default_timezone_set((string) ($this->config['app.timezone'] ?? 'Asia/Almaty'));
        if (session_status() === PHP_SESSION_NONE) {
            session_name((string) env('SESSION_COOKIE', 'tildamu_session'));
            session_start();
        }
        $_SESSION['language'] ??= (string) env('APP_LOCALE', 'ru');
    }

    public static function boot(): self
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }

        Env::load(base_path('.env'));
        require_once __DIR__ . '/helpers.php';

        spl_autoload_register(function (string $class): void {
            $prefix = 'App\\';
            if (!str_starts_with($class, $prefix)) {
                return;
            }
            $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
            $file = base_path('app/' . $relative . '.php');
            if (is_file($file)) {
                require_once $file;
            }
        });

        $app = new self();
        (new AutoInstaller())->run();
        require base_path('routes/web.php');
        require base_path('routes/api.php');
        return $app;
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            throw new \RuntimeException('Application is not booted.');
        }
        return self::$instance;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        $this->handleLocale();
        $this->router->dispatch(new Request());
    }

    public function config(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    private function handleLocale(): void
    {
        $lang = $_GET['lang'] ?? null;
        if (in_array($lang, ['ru', 'kk', 'en'], true)) {
            $_SESSION['language'] = $lang;
        }
    }
}
