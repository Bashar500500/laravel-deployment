<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeBookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $course = $this->resource;
        $course->load('assessments.assessmentSubmits', 'students.student');

        $assessments = $course->assessments;
        $students = $course->students->pluck('user');

        $studentRows = $this->buildStudentRows($students, $assessments);
        $assessmentSummaries = $this->buildAssessmentSummaries($assessments);
        $classAverage = $this->calculateClassAverage($studentRows);

        return [
            'course_id' => $course->id,
            'course_name' => $course->name,
            'totalStudents' => $students->count(),
            'assessments' => $assessmentSummaries,
            'students' => $studentRows,
            'classAverage' => $classAverage,
        ];
    }

    private function buildStudentRows($students, $assessments): array
    {
        $rows = [];

        foreach ($students as $student) {
            $row = [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'scores' => [],
                'average' => null,
            ];

            $totalScore = 0;
            $count = 0;

            foreach ($assessments as $assessment) {
                $submit = $assessment->assessmentSubmits->firstWhere('student_id', $student->id);
                $score = $submit?->score;

                $row['scores'][] = [
                    'assessment_id' => $assessment->id,
                    'assessment_title' => $assessment->title,
                    'score' => $score,
                    'weight' => $assessment->weight,
                ];

                if ($score !== null) {
                    $totalScore += $score;
                    $count++;
                }
            }

            $row['average'] = $count > 0 ? round($totalScore / $count, 2) : null;
            $rows[] = $row;
        }

        return $rows;
    }

    private function buildAssessmentSummaries($assessments): array
    {
        return $assessments->map(function ($assessment) {
            $scores = $assessment->assessmentSubmits->pluck('score')->filter();
            return [
                'id' => $assessment->id,
                'title' => $assessment->title,
                'weight' => $assessment->weight,
                'average' => $scores->count() > 0 ? round($scores->avg(), 2) : null,
            ];
        })->toArray();
    }

    private function calculateClassAverage(array $studentRows): ?float
    {
        $averages = collect($studentRows)->pluck('average')->filter();
        return $averages->count() > 0 ? round($averages->avg(), 2) : null;
    }
}
