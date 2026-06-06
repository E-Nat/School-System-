<?php
namespace App\Models;

use App\Core\Model;

class Program extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT p.*, d.name AS department_name FROM programs p LEFT JOIN departments d ON p.department_id = d.id ORDER BY p.name ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM programs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
