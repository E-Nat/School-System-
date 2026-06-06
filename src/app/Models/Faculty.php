<?php
namespace App\Models;

use App\Core\Model;

class Faculty extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM faculties ORDER BY name ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM faculties WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
