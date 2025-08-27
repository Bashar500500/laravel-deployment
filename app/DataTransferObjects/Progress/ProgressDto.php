<?php

namespace App\DataTransferObjects\Progress;

use App\Http\Requests\Progress\ProgressRequest;
use App\Enums\Progress\ProgressSkillLevel;

class ProgressDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $courseId,
        public readonly ?int $studentId,
        public readonly ?int $progress,
        public readonly ?string $modules,
        public readonly ?string $lastActive,
        public readonly ?string $streak,
        public readonly ?ProgressSkillLevel $skillLevel,
        public readonly ?string $upcomig,
    ) {}

    public static function fromIndexRequest(ProgressRequest $request): ProgressDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            courseId: null,
            studentId: null,
            progress: null,
            modules: null,
            lastActive: null,
            streak: null,
            skillLevel: null,
            upcomig: null,
        );
    }

    public static function fromStoreRequest(ProgressRequest $request): ProgressDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            studentId: $request->validated('student_id'),
            progress: $request->validated('progress'),
            modules: $request->validated('modules'),
            lastActive: $request->validated('last_active'),
            streak: $request->validated('streak'),
            skillLevel: ProgressSkillLevel::from($request->validated('skill_level')),
            upcomig: $request->validated('upcomig'),
        );
    }

    public static function fromUpdateRequest(ProgressRequest $request): ProgressDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            studentId: null,
            progress: $request->validated('progress'),
            modules: $request->validated('modules'),
            lastActive: $request->validated('last_active'),
            streak: $request->validated('streak'),
            skillLevel: $request->validated('skill_level') ?
                ProgressSkillLevel::from($request->validated('skill_level')) :
                null,
            upcomig: $request->validated('upcomig'),
        );
    }
}
