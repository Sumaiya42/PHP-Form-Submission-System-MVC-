<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Model
{
    protected PDO $db;

    public function __construct()
    {
        // Load environment variables
        if (!defined('BASE_PATH')) {
            define('BASE_PATH', dirname(dirname(__DIR__)));
        }
        $dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
        $dotenv->safeLoad();

        $host = $_ENV['DB_HOST'] ?? 'db';
        $db   = $_ENV['DB_NAME'] ?? 'pure_php_db';
        $user = $_ENV['DB_USER'] ?? 'user';
        $pass = $_ENV['DB_PASS'] ?? 'password';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->db = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
      
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
