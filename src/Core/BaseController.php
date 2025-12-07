<?php

declare(strict_types=1);

namespace App\Core;

class BaseController
{
    
    protected function render(string $viewName, array $data = []): void
    {
        // Extract data variables so they can be accessed directly in the view
        extract($data);

        ob_start();

       
        $viewPath = BASE_PATH . '/src/View/' . $viewName . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "Error: View file not found: " . htmlspecialchars($viewPath);
        }

        ob_end_flush();
    }

    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
