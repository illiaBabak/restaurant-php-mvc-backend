<?php

declare(strict_types=1);

namespace Core;

final class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, string $action): void
    {
        $pattern = preg_replace_callback(
            '#\{(\w+)(?::([^}]+))?\}#',
            fn($m) => '(?P<' . $m[1] . '>' . ($m[2] ?? '[^/]+') . ')',
            $path
        );

        $this->routes[strtoupper($method)][] = [
            'pattern' => '#^' . $pattern . '$#',
            'action' => $action
        ];
    }

    public function get(string $path, string $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, string $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    public function put(string $path, string $action): void
    {
        $this->addRoute('PUT', $path, $action);
    }

    public function delete(string $path, string $action): void
    {
        $this->addRoute('DELETE', $path, $action);
    }

    public function dispatch(string $uri, string $method = 'GET'): void
    {
        $path = '/' . trim(parse_url($uri, PHP_URL_PATH), '/');
        $method = strtoupper($method);
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                [$controllerClass, $action] = explode('@', $route['action']);

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller $controllerClass not found");
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $action)) {
                    throw new \Exception("Action $action not found in controller $controllerClass");
                }

                echo call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        http_response_code(404);
        echo "Not Found";
    }
}
