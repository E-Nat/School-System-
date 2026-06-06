<?php
namespace App\Models;

use App\Core\Model;

class Enrollment extends Model
{
    public function byStudent(int $studentId): array
    {
        $stmt = $this->db->prepare('SELECT e.*, c.course_name, c.course_code FROM enrollments e JOIN course_offerings co ON e.course_offering_id = co.id JOIN courses c ON co.course_id = c.id WHERE e.student_id = ?');
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function add(int $studentId, int $offeringId, ?int $sectionId = null): int
    {
        $stmt = $this->db->prepare('INSERT INTO enrollments (student_id, course_offering_id, section_id, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([$studentId, $offeringId, $sectionId, 'pending']);
        return (int)$this->db->lastInsertId();
    }
}
