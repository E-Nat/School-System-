<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config.php';
            $db = $config['db'];
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $db['host'], $db['dbname'], $db['charset']);

            try {
                self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                self::ensureCurrentSchema(self::$pdo);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    private static function ensureCurrentSchema(PDO $pdo): void
    {
        try {
            $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
            $column = $stmt ? $stmt->fetch() : false;
            $type = $column['Type'] ?? '';

            if ($type && !str_contains($type, 'super_admin')) {
                $pdo->beginTransaction();

                if (str_contains($type, 'admin') || str_contains($type, 'teacher') || str_contains($type, 'student')) {
                    $pdo->exec("UPDATE users SET role = 'super_admin' WHERE role = 'admin'");
                    $pdo->exec("UPDATE users SET role = 'lecturer' WHERE role = 'teacher'");
                    $pdo->exec("UPDATE users SET role = 'super_admin' WHERE email = 'superadmin@university.local'");
                    $pdo->exec("UPDATE users SET role = 'registrar' WHERE email = 'registrar@university.local'");
                    $pdo->exec("UPDATE users SET role = 'dean' WHERE email = 'dean@university.local'");
                    $pdo->exec("UPDATE users SET role = 'department_head' WHERE email = 'headdept@university.local'");
                    $pdo->exec("UPDATE users SET role = 'lecturer' WHERE email = 'lecturer@university.local'");
                    $pdo->exec("UPDATE users SET role = 'student' WHERE email = 'student@university.local'");
                    $pdo->exec("UPDATE users SET role = 'finance_staff' WHERE email = 'finance@university.local'");
                }

                $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','registrar','dean','department_head','lecturer','student','finance_staff') NOT NULL DEFAULT 'student'");
                $pdo->commit();
            }

            if ($type && str_contains($type, 'super_admin')) {
                $pdo->beginTransaction();
                $pdo->exec("UPDATE users SET role = 'super_admin' WHERE email = 'superadmin@university.local' AND role = ''");
                $pdo->exec("UPDATE users SET role = 'registrar' WHERE email = 'registrar@university.local' AND role = ''");
                $pdo->exec("UPDATE users SET role = 'dean' WHERE email = 'dean@university.local' AND role = ''");
                $pdo->exec("UPDATE users SET role = 'department_head' WHERE email = 'headdept@university.local' AND role = ''");
                $pdo->exec("UPDATE users SET role = 'lecturer' WHERE email = 'lecturer@university.local' AND role = ''");
                $pdo->exec("UPDATE users SET role = 'student' WHERE email = 'student@university.local' AND role = ''");
                $pdo->exec("UPDATE users SET role = 'finance_staff' WHERE email = 'finance@university.local' AND role = ''");
                $pdo->commit();
            }
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
        }
    }
}
