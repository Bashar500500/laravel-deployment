<?php

namespace App\DataTransferObjects\CommunityAccess;

use App\Http\Requests\CommunityAccess\CommunityAccessRequest;
use App\Enums\CommunityAccess\CommunityAccessAccessCourseDiscussions;
use App\Enums\CommunityAccess\CommunityAccessCourseDiscussionsLevel;
use App\Enums\CommunityAccess\CommunityAccessInboxCommunication;

class CommunityAccessCourseDto
{
    public function __construct(
        public readonly ?bool $courseDiscussionsEnabled,
        public readonly ?CommunityAccessPermissionsDto $communityAccessPermissionsDto,
        public readonly ?CommunityAccessReactionsDto $communityAccessReactionsDto,
        public readonly ?CommunityAccessAttachmentsDto $communityAccessAttachmentsDto,
        public readonly ?CommunityAccessAccessCourseDiscussions $accessCourseDiscussions,
        public readonly ?CommunityAccessCourseDiscussionsLevel $courseDiscussionsLevel,
        public readonly ?CommunityAccessInboxCommunication $inboxCommunication,
    ) {}

    public static function from(CommunityAccessRequest $request): CommunityAccessCourseDto
    {
        return new self(
            courseDiscussionsEnabled: $request->validated('course.course_discussions_enabled'),
            communityAccessPermissionsDto: CommunityAccessPermissionsDto::from($request),
            communityAccessReactionsDto: CommunityAccessReactionsDto::from($request),
            communityAccessAttachmentsDto: CommunityAccessAttachmentsDto::from($request),
            accessCourseDiscussions: $request->validated('course.access_course_discussions') ?
                CommunityAccessAccessCourseDiscussions::from($request->validated('course.access_course_discussions')) :
                null,
            courseDiscussionsLevel: $request->validated('course.course_discussions_level') ?
                CommunityAccessCourseDiscussionsLevel::from($request->validated('course.course_discussions_level')) :
                null,
            inboxCommunication: $request->validated('course.inbox_communication') ?
                CommunityAccessInboxCommunication::from($request->validated('course.inbox_communication')) :
                null,
        );
    }
}
