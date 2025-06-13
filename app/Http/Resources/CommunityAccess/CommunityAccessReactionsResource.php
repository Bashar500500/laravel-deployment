<?php

namespace App\Http\Resources\CommunityAccess;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityAccessReactionsResource extends JsonResource
{
    public static function makeJson(
        CommunityAccessResource $communityAccessResource,
    ): array
    {
        return [
            'upvoteEnabled' => $communityAccessResource->reactions_upvote_enabled == 0 ? 'false' : 'true',
            'likeEnabled' => $communityAccessResource->reactions_like_enabled == 0 ? 'false' : 'true',
            'shareEnabled' => $communityAccessResource->reactions_share_enabled == 0 ? 'false' : 'true',
        ];
    }
}
