<?php

namespace App\DataTransferObjects\Grade;

use App\Http\Requests\Grade\GradeRequest;
use App\Enums\Grade\GradeStatus;
use App\Enums\Grade\GradeCategory;
use App\Enums\Grade\GradeTrend;
use App\Enums\Grade\GradeResubmission;
use Illuminate\Support\Carbon;

class GradeDto
{
    public function __construct(
        public readonly ?int $currentPage,
        public readonly ?int $pageSize,
        public readonly ?int $assignmentId,
        public readonly ?int $studentId,
        public readonly ?Carbon $dueDate,
        public readonly ?Carbon $extendedDueDate,
        public readonly ?GradeStatus $status,
        public readonly ?int $pointsEarned,
        public readonly ?int $maxPoints,
        public readonly ?int $percentage,
        public readonly ?GradeCategory $category,
        public readonly ?float $classAverage,
        public readonly ?GradeTrend $trend,
        public readonly ?array $trendData,
        public readonly ?string $feedback,
        public readonly ?GradeResubmission $resubmission,
        public readonly ?Carbon $resubmissionDue,
    ) {}

    public static function fromIndexRequest(GradeRequest $request): GradeDto
    {
        return new self(
            currentPage: $request->validated('page'),
            pageSize: $request->validated('page_size') ?? 20,
            assignmentId: null,
            studentId: null,
            dueDate: null,
            extendedDueDate: null,
            status: null,
            pointsEarned: null,
            maxPoints: null,
            percentage: null,
            category: null,
            classAverage: null,
            trend: null,
            trendData: null,
            feedback: null,
            resubmission: null,
            resubmissionDue: null,
        );
    }

    public static function fromStoreRequest(GradeRequest $request): GradeDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assignmentId: $request->validated('assignment_id'),
            studentId: $request->validated('student_id'),
            dueDate: Carbon::parse($request->validated('due_date')),
            extendedDueDate: Carbon::parse($request->validated('extended_due_date')),
            status: GradeStatus::from($request->validated('status')),
            pointsEarned: $request->validated('points_earned'),
            maxPoints: $request->validated('max_points'),
            percentage: $request->validated('percentage'),
            category: GradeCategory::from($request->validated('category')),
            classAverage: $request->validated('class_average'),
            trend: GradeTrend::from($request->validated('trend')),
            trendData: $request->validated('trend_data'),
            feedback: $request->validated('feedback'),
            resubmission: GradeResubmission::from($request->validated('resubmission')),
            resubmissionDue: Carbon::parse($request->validated('resubmission_due')),
        );
    }

    public static function fromUpdateRequest(GradeRequest $request): GradeDto
    {
        return new self(
            currentPage: null,
            pageSize: null,
            assignmentId: null,
            studentId: null,
            dueDate: $request->validated('due_date') ?
                Carbon::parse($request->validated('due_date')) :
                null,
            extendedDueDate: $request->validated('extended_due_date') ?
                Carbon::parse($request->validated('extended_due_date')) :
                null,
            status: $request->validated('status') ?
                GradeStatus::from($request->validated('status')) :
                null,
            pointsEarned: $request->validated('points_earned'),
            maxPoints: $request->validated('max_points'),
            percentage: $request->validated('percentage'),
            category: $request->validated('category') ?
                GradeCategory::from($request->validated('category')) :
                null,
            classAverage: $request->validated('class_average'),
            trend: $request->validated('trend') ?
                GradeTrend::from($request->validated('trend')) :
                null,
            trendData: $request->validated('trend_data'),
            feedback: $request->validated('feedback'),
            resubmission: $request->validated('resubmission') ?
                GradeResubmission::from($request->validated('resubmission')) :
                null,
            resubmissionDue: $request->validated('resubmission_due') ?
                Carbon::parse($request->validated('resubmission_due')) :
                null,
        );
    }
}
