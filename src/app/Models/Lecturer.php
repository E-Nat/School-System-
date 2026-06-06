<?php
namespace App\Models;

use App\Core\Model;

class Lecturer extends Model
{
    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM teachers WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function all(): array
    {
        return $this->db->query('SELECT t.*, u.email, d.name AS department_name FROM teachers t LEFT JOIN users u ON t.user_id = u.id LEFT JOIN departments d ON t.department_id = d.id ORDER BY u.last_name ASC')->fetchAll();
    }

    public function createProfile(int $userId, string $employeeId, string $position): int
    {
        $stmt = $this->db->prepare('INSERT INTO teachers (user_id, employee_id, position, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $employeeId, $position, 'active']);
        return (int)$this->db->lastInsertId();
    }
}
