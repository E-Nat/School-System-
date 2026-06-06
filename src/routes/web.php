<?php
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

return [
    'GET' => [
        '/' => [DashboardController::class, 'home'],
        '/login' => [AuthController::class, 'login'],
        '/register' => [AuthController::class, 'register'],
        '/logout' => [AuthController::class, 'logout'],
        '/admin' => [DashboardController::class, 'admin'],
        '/lecturer' => [DashboardController::class, 'lecturer'],
        '/student' => [DashboardController::class, 'student'],
    ],
    'POST' => [
        '/login' => [AuthController::class, 'login'],
        '/register' => [AuthController::class, 'register'],
    ],
];
