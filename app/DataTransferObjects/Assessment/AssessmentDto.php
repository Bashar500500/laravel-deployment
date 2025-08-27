<?php

namespace App\DataTransferObjects\Assessment;

use App\Http\Requests\Assessment\AssessmentRequest;
use App\Enums\Assessment\AssessmentType;
use App\Enums\Assessment\AssessmentStatus;
use Illuminate\Support\Carbon;

class AssessmentDto
{
    public function __construct(
        public readonly ?int $courseId,
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $timeLimitId,
        public readonly ?AssessmentType $type,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?AssessmentStatus $status,
        public readonly ?int $weight,
        public readonly ?Carbon $availableFrom,
        public readonly ?Carbon $availableTo,
        public readonly ?int $attemptsAllowed,
        public readonly ?bool $shuffleQuestions,
        public readonly ?array $feedbackOptions,
    ) {}

    public static function fromIndexRequest(AssessmentRequest $request): AssessmentDto
    {
        return new self(
            courseId: $request->validated('course_id'),
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            timeLimitId: null,
            type: null,
            title: null,
            description: null,
            status: null,
            weight: null,
            availableFrom: null,
            availableTo: null,
            attemptsAllowed: null,
            shuffleQuestions: null,
            feedbackOptions: null,
        );
    }

    public static function fromStoreRequest(AssessmentRequest $request): AssessmentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: $request->validated('course_id'),
            timeLimitId: $request->validated('time_limit_id'),
            type: AssessmentType::from($request->validated('type')),
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: AssessmentStatus::from($request->validated('status')),
            weight: $request->validated('weight'),
            availableFrom: Carbon::parse($request->validated('available_from')),
            availableTo: Carbon::parse($request->validated('available_to')),
            attemptsAllowed: $request->validated('attempts_allowed'),
            shuffleQuestions: $request->validated('shuffle_questions'),
            feedbackOptions: $request->validated('feedback_options'),
        );
    }

    public static function fromUpdateRequest(AssessmentRequest $request): AssessmentDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            courseId: null,
            timeLimitId: $request->validated('time_limit_id'),
            type: $request->validated('type') ?
                AssessmentType::from($request->validated('type')) :
                null,
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: $request->validated('type') ?
                AssessmentStatus::from($request->validated('status')) :
                null,
            weight: $request->validated('weight'),
            availableFrom: $request->validated('available_from') ?
                Carbon::parse($request->validated('available_from')) :
                null,
            availableTo: $request->validated('available_to') ?
                Carbon::parse($request->validated('available_to')) :
                null,
            attemptsAllowed: $request->validated('attempts_allowed'),
            shuffleQuestions: $request->validated('shuffle_questions'),
            feedbackOptions: $request->validated('feedback_options'),
        );
    }
}
