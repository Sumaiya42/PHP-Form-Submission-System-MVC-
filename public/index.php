<?php

declare(strict_types=1);

use App\Core\Router;


define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

// Basic error reporting for development
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


session_start();


$router = new Router();

// --- Define Routes ---

// Public Routes
$router->add('/', 'Home', 'index');
$router->add('/login', 'Auth', 'login');
$router->add('/signup', 'Auth', 'signup');
$router->add('/logout', 'Auth', 'logout');
$router->add('/api/signup', 'Auth', 'handleSignup', false, 'POST');
$router->add('/api/login', 'Auth', 'handleLogin', false, 'POST');

// Authenticated Routes
$router->add('/submit', 'Submission', 'index', true);
$router->add('/api/submit', 'Submission', 'handleSubmission', true, 'POST');
$router->add('/report', 'Report', 'index', true);
$router->add('/api/report', 'Report', 'getReportData', true, 'GET');

// Dispatch the request
$router->dispatch();
