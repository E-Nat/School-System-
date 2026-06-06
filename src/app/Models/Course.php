<?php
namespace App\Models;

use App\Core\Model;

class Course extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT c.*, p.name AS program_name FROM courses c LEFT JOIN programs p ON c.program_id = p.id ORDER BY c.course_name ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
