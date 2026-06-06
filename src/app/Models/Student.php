<?php
namespace App\Models;

use App\Core\Model;

class Student extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT s.*, u.email, p.name AS program_name FROM students s LEFT JOIN users u ON s.user_id = u.id LEFT JOIN programs p ON s.program_id = p.id ORDER BY s.student_number ASC')->fetchAll();
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function createProfile(int $userId, string $studentNumber): int
    {
        $stmt = $this->db->prepare('INSERT INTO students (user_id, student_id, student_number, admission_year, current_year, current_semester, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $studentNumber, $studentNumber, date('Y'), 'Year 1', 'Semester 1', 'active']);
        return (int)$this->db->lastInsertId();
    }
}
