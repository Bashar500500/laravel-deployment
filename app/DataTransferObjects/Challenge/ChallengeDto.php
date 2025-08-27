<?php

namespace App\DataTransferObjects\Challenge;

use App\Http\Requests\Challenge\ChallengeRequest;
use App\Enums\Challenge\ChallengeType;
use App\Enums\Challenge\ChallengeCategory;
use App\Enums\Challenge\ChallengeDifficulty;
use App\Enums\Challenge\ChallengeStatus;

class ChallengeDto
{
    public function __construct(
        public readonly ?int $instructorId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?string $title,
        public readonly ?ChallengeType $type,
        public readonly ?ChallengeCategory $category,
        public readonly ?ChallengeDifficulty $difficulty,
        public readonly ?ChallengeStatus $status,
        public readonly ?string $description,
        public readonly ?array $courses,
        public readonly ?array $rules,
        public readonly ?array $badges,
        public readonly ?array $conditions,
        public readonly ?array $rewards,
    ) {}

    public static function fromIndexRequest(ChallengeRequest $request): ChallengeDto
    {
        return new self(
            instructorId: null,
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            title: null,
            type: null,
            category: null,
            difficulty: null,
            status: null,
            description: null,
            courses: null,
            rules: null,
            badges: null,
            conditions: null,
            rewards: null,
        );
    }

    public static function fromStoreRequest(ChallengeRequest $request): ChallengeDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            type: ChallengeType::from($request->validated('type')),
            category: ChallengeCategory::from($request->validated('category')),
            difficulty: ChallengeDifficulty::from($request->validated('difficulty')),
            status: $request->validated('status') ?
                ChallengeStatus::from($request->validated('status')) :
                null,
            description: $request->validated('description'),
            courses: $request->validated('courses'),
            rules: $request->validated('rules'),
            badges: $request->validated('badges'),
            conditions: $request->validated('conditions'),
            rewards: $request->validated('rewards'),
        );
    }

    public static function fromUpdateRequest(ChallengeRequest $request): ChallengeDto
    {
        return new self(
            instructorId: null,
            currentPage: null,
            pageSize: null,
            title: $request->validated('title'),
            type: $request->validated('type') ?
                ChallengeType::from($request->validated('type')) :
                null,
            category: $request->validated('category') ?
                ChallengeCategory::from($request->validated('category')) :
                null,
            difficulty: $request->validated('difficulty') ?
                ChallengeDifficulty::from($request->validated('difficulty')) :
                null,
            status: $request->validated('status') ?
                ChallengeStatus::from($request->validated('status')) :
                null,
            description: $request->validated('description'),
            courses: $request->validated('courses'),
            rules: $request->validated('rules'),
            badges: $request->validated('badges'),
            conditions: $request->validated('conditions'),
            rewards: $request->validated('rewards'),
        );
    }
}
