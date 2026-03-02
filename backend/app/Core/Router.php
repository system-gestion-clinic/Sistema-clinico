<?php
namespace App\Core;

use App\Core\Response;

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function add(string $method, string $path, callable $handler): void
    {
        $method = strtoupper($method);
        $path = rtrim($path, '/') ?: '/';
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = rtrim($path, '/') ?: '/';

        // suporte a rotas com {id}
        foreach (($this->routes[$method] ?? []) as $route => $handler) {
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([0-9]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $handler(...$matches);
                return;
            }
        }

        Response::error('Rota não encontrada', 404, ['path' => $path, 'method' => $method]);
    }
}
