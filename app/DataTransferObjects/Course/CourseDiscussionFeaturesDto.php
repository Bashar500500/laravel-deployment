<?php

namespace App\DataTransferObjects\Course;

use App\Http\Requests\Course\CourseRequest;

class CourseDiscussionFeaturesDto
{
    public function __construct(
        public readonly ?bool $attachFiles,
        public readonly ?bool $createTopics,
        public readonly ?bool $editReplies,
    ) {}

    public static function from(CourseRequest $request): CourseDiscussionFeaturesDto
    {
        return new self(
            attachFiles: $request->validated('features.discussion_features.attach_files'),
            createTopics: $request->validated('features.discussion_features.create_topics'),
            editReplies: $request->validated('features.discussion_features.edit_replies'),
        );
    }
}
