<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $normalized = normalize_path($path);
        $this->routes[$method][$normalized] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = normalize_path($request->path());

        $handler = $this->routes[$method][$path] ?? null;
        if ($handler === null && $path === '/') {
            $handler = $this->routes[$method]['/'] ?? null;
        }

        if ($handler === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        if (is_array($handler)) {
            [$class, $action] = $handler;
            $controller = new $class();
            $controller->{$action}($request);
            return;
        }

        $handler($request);
    }
}
