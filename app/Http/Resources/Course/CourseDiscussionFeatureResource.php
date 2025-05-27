<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseDiscussionFeatureResource extends JsonResource
{
    public static function makeJson(
        CourseResource $courseResource,
    ): array
    {
        return [
            'attachFiles' => $courseResource->features_discussion_features_attach_files == 0 ? 'false' : 'true',
            'createTopics' => $courseResource->features_discussion_features_create_topics == 0 ? 'false' : 'true',
            'editReplies' => $courseResource->features_discussion_features_edit_replies == 0 ? 'false' : 'true',
        ];
    }
}
