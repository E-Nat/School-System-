<?php
namespace App\Services;

class GradeService
{
    public static function calculateFinalGrade(array $gradeComponents): array
    {
        $totalWeight = array_sum(array_column($gradeComponents, 'weight'));
        $scoreSum = 0.0;

        foreach ($gradeComponents as $component) {
            $scoreSum += ($component['score'] * ($component['weight'] / 100));
        }

        $finalScore = $totalWeight > 0 ? $scoreSum : 0;
        $gradeValue = self::mapScoreToGrade($finalScore);

        return [
            'score' => round($finalScore, 2),
            'grade_value' => $gradeValue,
        ];
    }

    private static function mapScoreToGrade(float $score): string
    {
        if ($score >= 85) {
            return 'A';
        }
        if ($score >= 70) {
            return 'B';
        }
        if ($score >= 55) {
            return 'C';
        }
        if ($score >= 40) {
            return 'D';
        }
        return 'F';
    }
}
