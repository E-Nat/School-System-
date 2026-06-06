<?php
return [
    'app' => [
        'name' => 'University Management System',
        'base_url' => '/',
        'timezone' => 'UTC',
    ],
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'school_system',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'session_name' => 'ums_session',
        'csrf_token_key' => 'csrf_token',
    ],
];
