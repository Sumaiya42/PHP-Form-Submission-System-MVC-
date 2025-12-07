<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller\AuthController;

class Router
{
    protected array $routes = [];

    public function add(string $uri, string $controller, string $action, bool $authRequired = false, string $method = 'GET'): void
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => "App\\Controller\\{$controller}Controller",
            'action' => $action,
            'auth' => $authRequired,
            'method' => strtoupper($method)
        ];
    }


    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $requestMethod) {
                // Check for authentication
                if ($route['auth'] && !isset($_SESSION['user_id'])) {
                    header('Location: /login');
                    exit;
                }

                $controllerClass = $route['controller'];
                $action = $route['action'];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $action)) {
                        $controller->$action();
                        return;
                    }
                }
            }
        }

        // Handle 404 Not Found
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
    }
}
