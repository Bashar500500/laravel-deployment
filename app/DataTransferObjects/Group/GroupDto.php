<?php

namespace App\DataTransferObjects\Group;

use App\Http\Requests\Group\GroupRequest;
use App\Enums\Group\GroupStatus;
use Illuminate\Http\UploadedFile;

class GroupDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?GroupStatus $status,
        public readonly ?UploadedFile $image,
        public readonly ?array $students,
        public readonly ?GroupCapacityDto $groupCapacityDto,
    ) {}

    public static function fromIndexRequest(GroupRequest $request): GroupDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            name: null,
            description: null,
            status: null,
            image: null,
            students: null,
            groupCapacityDto: null,
        );
    }

    public static function fromStoreRequest(GroupRequest $request): GroupDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            status: GroupStatus::from($request->validated('status')),
            image: $request->validated('image') ?
                UploadedFile::createFromBase($request->validated('image')) :
                null,
            students: $request->validated('students'),
            groupCapacityDto: GroupCapacityDto::from($request),
        );
    }

    public static function fromUpdateRequest(GroupRequest $request): GroupDto
    {
        return new self(
            courseId: null,
            currentPage: null,
            pageSize: null,
            name: $request->validated('name'),
            description: $request->validated('description'),
            status: $request->validated('status') ?
                GroupStatus::from($request->validated('status')) :
                null,
            image: $request->validated('image') ?
                UploadedFile::createFromBase($request->validated('image')) :
                null,
            students: null,
            groupCapacityDto: GroupCapacityDto::from($request),
        );
    }
}
