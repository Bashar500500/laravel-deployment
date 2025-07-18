<?php

namespace App\Http\Resources\Rule;

use Illuminate\Http\Resources\Json\JsonResource;

class RuleMetadataResource extends JsonResource
{
    public static function makeJson(
        RuleResource $ruleResource,
    ): array
    {
        return [
            'createdAt' => $ruleResource->created_at,
            'updatedAt' => $ruleResource->updated_at,
            'totalAwards' => $ruleResource->userAwards->count(),
            'recentAwards' => RuleRecentAwardsResource::collection($ruleResource->userAwards->take(-5)),
        ];
    }
}
