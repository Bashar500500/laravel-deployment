<?php

namespace App\DataTransferObjects\LearningGap;

use App\Http\Requests\LearningGap\LearningGapRequest;
use App\Enums\LearningGap\LearningGapTargetRole;
use App\Enums\LearningGap\LearningGapCurrentLevel;
use App\Enums\LearningGap\LearningGapRequiredLevel;
use App\Enums\LearningGap\LearningGapGapSize;
use App\Enums\LearningGap\LearningGapPriority;
use App\Enums\LearningGap\LearningGapStatus;

class LearningGapDto
{
    public function __construct(
        public readonly ?int $studentId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $skillId,
        public readonly ?LearningGapTargetRole $targetRole,
        public readonly ?LearningGapCurrentLevel $currentLevel,
        public readonly ?LearningGapRequiredLevel $requiredLevel,
        public readonly ?LearningGapGapSize $gapSize,
        public readonly ?LearningGapPriority $priority,
        public readonly ?float $gapScore,
        public readonly ?LearningGapStatus $status,
    ) {}

    public static function fromIndexRequest(LearningGapRequest $request): LearningGapDto
    {
        return new self(
            studentId: $request->validated('student_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            skillId: null,
            targetRole: null,
            currentLevel: null,
            requiredLevel: null,
            gapSize: null,
            priority: null,
            gapScore: null,
            status: null,
        );
    }

    public static function fromStoreRequest(LearningGapRequest $request): LearningGapDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            studentId: $request->validated('student_id'),
            skillId: $request->validated('skill_id'),
            targetRole: LearningGapTargetRole::from($request->validated('target_role')),
            currentLevel: LearningGapCurrentLevel::from($request->validated('current_level')),
            requiredLevel: LearningGapRequiredLevel::from($request->validated('required_level')),
            gapSize: LearningGapGapSize::from($request->validated('gap_size')),
            priority: LearningGapPriority::from($request->validated('priority')),
            gapScore: $request->validated('gap_score'),
            status: LearningGapStatus::from($request->validated('status')),
        );
    }

    public static function fromUpdateRequest(LearningGapRequest $request): LearningGapDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            studentId: null,
            skillId: $request->validated('skill_id'),
            targetRole: $request->validated('target_role') ?
                LearningGapTargetRole::from($request->validated('target_role')) :
                null,
            currentLevel: $request->validated('current_level') ?
                LearningGapCurrentLevel::from($request->validated('current_level')) :
                null,
            requiredLevel: $request->validated('required_level') ?
                LearningGapRequiredLevel::from($request->validated('required_level')) :
                null,
            gapSize: $request->validated('gap_size') ?
                LearningGapGapSize::from($request->validated('gap_size')) :
                null,
            priority: $request->validated('priority') ?
                LearningGapPriority::from($request->validated('priority')) :
                null,
            gapScore: $request->validated('content'),
            status: $request->validated('status') ?
                LearningGapStatus::from($request->validated('status')) :
                null,
        );
    }
}
