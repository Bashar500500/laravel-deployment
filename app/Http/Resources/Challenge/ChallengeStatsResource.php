<?php

namespace App\Http\Resources\Challenge;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeStatsResource extends JsonResource
{
    public static function makeJson(
        ChallengeResource $challengeResource,
    ): array
    {
        return [
            'participants' => $challengeResource->whenLoaded('challengeUsers')->count(),
            'completions' => $challengeResource->whenLoaded('userAwards')->count(),
            'averagePointsEarned' => ChallengeStatsResource::prepareAveragePointsEarnedData(
                $challengeResource->whenLoaded('userAwards')->sum('number'),
                $challengeResource->whenLoaded('challengeUsers')->count()
            ),
        ];
    }

    private static function prepareAveragePointsEarnedData(int $sumPoints, int $participants): float|int
    {
        return $participants == 0 ? 0 : ($sumPoints / $participants);
    }
}
