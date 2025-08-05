<?php

namespace App\Http\Resources\Assessment;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentStatsResource extends JsonResource
{
    public static function makeJson(
        AssessmentResource $assessmentResource,
    ): array
    {
        return [
            'totalStudents' => $assessmentResource->whenLoaded('course')->students->count(),
            'submissions' => $assessmentResource->whenLoaded('assessmentSubmits')->count(),
            'graded' => $assessmentResource->whenLoaded('grades')->count(),
            'averageScore' => AssessmentStatsResource::prepareAverageScoreData(
                $assessmentResource->whenLoaded('assessmentSubmits')->sum('score'),
                $assessmentResource->whenLoaded('assessmentSubmits')->count()
            ),
        ];
    }

    private static function prepareAverageScoreData(int $sumScores, int $submits): float|int
    {
        return $submits == 0 ? 0 : ($sumScores / $submits);
    }
}
