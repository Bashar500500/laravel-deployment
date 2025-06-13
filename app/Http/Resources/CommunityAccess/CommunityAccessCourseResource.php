<?php

namespace App\Http\Resources\CommunityAccess;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityAccessCourseResource extends JsonResource
{
    public static function makeJson(
        CommunityAccessResource $communityAccessResource,
    ): array
    {
        return [
            'courseDiscussionsEnabled' => $communityAccessResource->course_discussions_enabled,
            'permissions' => CommunityAccessPermissionsResource::makeJson($communityAccessResource),
            'reactions' => CommunityAccessReactionsResource::makeJson($communityAccessResource),
            'attachments' => CommunityAccessAttachmentsResource::makeJson($communityAccessResource),
            'accessCourseDiscussions' => $communityAccessResource->access_course_discussions,
            'courseDiscussionsLevel' => $communityAccessResource->course_discussions_level,
            'inboxCommunication' => $communityAccessResource->inbox_communication,
        ];
    }
}
