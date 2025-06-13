<?php

namespace App\Http\Resources\CommunityAccess;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityAccessPermissionsResource extends JsonResource
{
    public static function makeJson(
        CommunityAccessResource $communityAccessResource,
    ): array
    {
        return [
            'postEnabled' => $communityAccessResource->permissions_post_enabled == 0 ? 'false' : 'true',
            'pollEnabled' => $communityAccessResource->permissions_poll_enabled == 0 ? 'false' : 'true',
            'commentEnabled' => $communityAccessResource->permissions_comment_enabled == 0 ? 'false' : 'true',
        ];
    }
}
