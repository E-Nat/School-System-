<?php
namespace App\Models;

use App\Core\Model;

class Attendance extends Model
{
    public function forEnrollment(int $enrollmentId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM attendance WHERE enrollment_id = ? ORDER BY attendance_date DESC');
        $stmt->execute([$enrollmentId]);
        return $stmt->fetchAll();
    }

    public function record(int $enrollmentId, string $status, ?string $remarks, int $recordedBy): int
    {
        $stmt = $this->db->prepare('INSERT INTO attendance (enrollment_id, attendance_date, status, remarks, recorded_by) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$enrollmentId, date('Y-m-d'), $status, $remarks, $recordedBy]);
        return (int)$this->db->lastInsertId();
    }
}
