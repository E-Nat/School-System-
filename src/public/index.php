<?php
require_once __DIR__ . '/../../autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Middleware\RoleMiddleware;

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routes = require __DIR__ . '/../routes/web.php';
$route = $routes[$method][$uri] ?? null;

if (!$route) {
    http_response_code(404);
    echo 'Page not found';
    exit;
}

[$controllerClass, $action] = $route;
$controller = new $controllerClass();

// Example role-based protection: can be extended in middleware
if (in_array($uri, ['/admin'], true) && !RoleMiddleware::authorizeAdmin($_SESSION['user'] ?? [])) {
    http_response_code(403);
    echo 'Access denied';
    exit;
}

$controller->$action();
