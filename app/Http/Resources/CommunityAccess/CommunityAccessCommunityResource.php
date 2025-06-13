<?php

namespace App\Http\Resources\CommunityAccess;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityAccessCommunityResource extends JsonResource
{
    public static function makeJson(
        CommunityAccessResource $communityAccessResource,
    ): array
    {
        return [
            'communityEnabled' => $communityAccessResource->community_enabled == 0 ? 'false' : 'true',
            'accessCommunity' => $communityAccessResource->access_community,
        ];
    }
}
