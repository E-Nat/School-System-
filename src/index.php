<?php
session_start();
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;

$page = $_GET['page'] ?? 'home';
$authController = new AuthController();
$dashboardController = new DashboardController();

switch ($page) {
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'register':
        $authController->register();
        break;
    case 'admin_dashboard':
        $dashboardController->admin();
        break;
    case 'lecturer_dashboard':
        $dashboardController->lecturer();
        break;
    case 'student_dashboard':
        $dashboardController->student();
        break;
    default:
        $dashboardController->home();
        break;
}

