<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin(array $allowedRoles = []) {
    if (!isset($_SESSION['user'])) {
        header('Location: sign_in.php');
        exit;
    }

    if ($allowedRoles && !in_array($_SESSION['user']['role'], $allowedRoles, true)) {
        header('Location: index.php');
        exit;
    }
}

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function hasRole(string $role) {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === $role;
}

function isSuperAdmin() {
    return hasRole('super_admin');
}

function isAdmin() {
    return isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['super_admin', 'admin', 'registrar', 'dean', 'department_head', 'finance_staff'], true);
}

function isLecturer() {
    return hasRole('lecturer');
}

function isStudent() {
    return hasRole('student');
}
