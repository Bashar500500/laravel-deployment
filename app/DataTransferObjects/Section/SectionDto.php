<?php

namespace App\DataTransferObjects\Section;

use App\Http\Requests\Section\SectionRequest;
use App\Enums\Section\SectionStatus;

class SectionDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?SectionStatus $status,
        public readonly ?SectionAccessDto $sectionAccessDto,
        public readonly ?array $groups,
        public readonly ?SectionResourcesDto $sectionResourcesDto,
    ) {}

    public static function fromIndexRequest(SectionRequest $request): SectionDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            title: null,
            description: null,
            status: null,
            sectionAccessDto: null,
            groups: null,
            sectionResourcesDto: null,
        );
    }

    public static function fromStoreRequest(SectionRequest $request): SectionDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: SectionStatus::from($request->validated('status')),
            sectionAccessDto: SectionAccessDto::from($request),
            groups: $request->validated('groups'),
            sectionResourcesDto: SectionResourcesDto::from($request),
        );
    }

    public static function fromUpdateRequest(SectionRequest $request): SectionDto
    {
        return new self(
            courseId: null,
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: $request->validated('status') ?
                SectionStatus::from($request->validated('status')) :
                null,
            sectionAccessDto: SectionAccessDto::from($request),
            groups: $request->validated('groups'),
            sectionResourcesDto: SectionResourcesDto::from($request),
        );
    }
}
