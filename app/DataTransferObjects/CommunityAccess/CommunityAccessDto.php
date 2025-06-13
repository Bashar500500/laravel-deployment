<?php

namespace App\DataTransferObjects\CommunityAccess;

use App\Http\Requests\CommunityAccess\CommunityAccessRequest;

class CommunityAccessDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?CommunityAccessCommunityDto $communityAccessCommunityDto,
        public readonly ?CommunityAccessCourseDto $communityAccessCourseDto,
    ) {}

    public static function fromIndexRequest(CommunityAccessRequest $request): CommunityAccessDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            communityAccessCommunityDto: null,
            communityAccessCourseDto: null,
        );
    }

    public static function fromStoreRequest(CommunityAccessRequest $request): CommunityAccessDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            communityAccessCommunityDto: CommunityAccessCommunityDto::from($request),
            communityAccessCourseDto: CommunityAccessCourseDto::from($request),
        );
    }

    public static function fromUpdateRequest(CommunityAccessRequest $request): CommunityAccessDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            communityAccessCommunityDto: CommunityAccessCommunityDto::from($request),
            communityAccessCourseDto: CommunityAccessCourseDto::from($request),
        );
    }
}
