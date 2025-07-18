<?php

namespace App\Http\Resources\Assignment;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentStatsResource extends JsonResource
{
    public static function makeJson(
        AssignmentResource $assignmentResource,
    ): array
    {
        return [
            'totalStudents' => $assignmentResource->whenLoaded('course')->students->count(),
            'submissions' => $assignmentResource->whenLoaded('assignmentSubmits')->count(),
            'graded' => $assignmentResource->whenLoaded('grades')->count(),
            'averageScore' => AssignmentStatsResource::prepareAverageScoreData(
                $assignmentResource->whenLoaded('assignmentSubmits')->sum('score'),
                $assignmentResource->whenLoaded('assignmentSubmits')->count()
            ),
        ];
    }

    private static function prepareAverageScoreData(int $sumScores, int $submits): float|int
    {
        return $submits == 0 ? 0 : ($sumScores / $submits);
    }
}
