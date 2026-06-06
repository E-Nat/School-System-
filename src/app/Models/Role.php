<?php
namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM roles ORDER BY name ASC')->fetchAll();
    }

    public function findByName(string $name): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM roles WHERE name = ?');
        $stmt->execute([$name]);
        return $stmt->fetch() ?: null;
    }
}
