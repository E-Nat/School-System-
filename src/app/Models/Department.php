<?php
namespace App\Models;

use App\Core\Model;

class Department extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT d.*, f.name AS faculty_name FROM departments d LEFT JOIN faculties f ON d.faculty_id = f.id ORDER BY d.name ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM departments WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
