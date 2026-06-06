<?php
namespace App\Middleware;

class RoleMiddleware
{
    public const ADMIN_ROLES = [
        'super_admin',
        'admin',
        'registrar',
        'dean',
        'department_head',
        'finance_staff',
    ];

    public const ALL_ROLES = [
        'super_admin',
        'admin',
        'registrar',
        'dean',
        'department_head',
        'finance_staff',
        'lecturer',
        'student',
    ];

    public static function authorize(array $allowedRoles, array $user): bool
    {
        if (empty($user) || empty($user['role'])) {
            return false;
        }

        return in_array($user['role'], $allowedRoles, true);
    }

    public static function authorizeAdmin(array $user): bool
    {
        return self::authorize(self::ADMIN_ROLES, $user);
    }

    public static function isAdmin(array $user): bool
    {
        return self::authorizeAdmin($user);
    }
}
