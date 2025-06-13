<?php

namespace App\Http\Resources\CommunityAccess;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityAccessAttachmentsResource extends JsonResource
{
    public static function makeJson(
        CommunityAccessResource $communityAccessResource,
    ): array
    {
        return [
            'imagesEnabled' => $communityAccessResource->attachments_images_enabled == 0 ? 'false' : 'true',
            'videosEnabled' => $communityAccessResource->attachments_videos_enabled == 0 ? 'false' : 'true',
            'filesEnabled' => $communityAccessResource->attachments_files_enabled == 0 ? 'false' : 'true',
        ];
    }
}
