<?php

namespace App\Http\Resources\Badge;

use Illuminate\Http\Resources\Json\JsonResource;

class BadgeMetadataResource extends JsonResource
{
    public static function makeJson(
        BadgeResource $badgeResource,
    ): array
    {
        return [
            'createdAt' => $badgeResource->created_at,
            'updatedAt' => $badgeResource->updated_at,
            'totalAwards' => $badgeResource->userAwards->count(),
            'recentAwards' => BadgeRecentAwardsResource::collection($badgeResource->userAwards->take(-5)),
        ];
    }
}
