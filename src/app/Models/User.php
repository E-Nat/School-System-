<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? AND status = ?');
        $stmt->execute([$email, 'active']);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (first_name, last_name, email, password, role, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            Security::sanitize($data['first_name']),
            Security::sanitize($data['last_name']),
            Security::sanitize($data['email']),
            password_hash($data['password'], PASSWORD_DEFAULT),
            Security::sanitize($data['role']),
            Security::sanitize($data['phone'] ?? ''),
            'active',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function logLogin(int $userId, string $ip): void
    {
        $stmt = $this->db->prepare('INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, 'login', 'User signed in', $ip]);
    }
}
