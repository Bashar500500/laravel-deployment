<?php

namespace App\DataTransferObjects\Course;

use App\Http\Requests\Course\CourseRequest;

class CourseFeaturesDto
{
    public function __construct(
        public readonly ?bool $personalizedLearningPaths,
        public readonly ?bool $certificateRequiresSubmission,
        public readonly ?CourseDiscussionFeaturesDto $discussionFeaturesDto,
        public readonly ?bool $studentGroups,
        public readonly ?bool $isFeatured,
        public readonly ?bool $showProgressScreen,
        public readonly ?bool $hideGradeTotals,
    ) {}

    public static function from(CourseRequest $request): CourseFeaturesDto
    {
        return new self(
            personalizedLearningPaths: $request->validated('features.personalized_learning_paths'),
            certificateRequiresSubmission: $request->validated('features.certificate_requires_submission'),
            discussionFeaturesDto: CourseDiscussionFeaturesDto::from($request),
            studentGroups: $request->validated('features.student_groups'),
            isFeatured: $request->validated('features.is_featured'),
            showProgressScreen: $request->validated('features.show_progress_screen'),
            hideGradeTotals: $request->validated('features.hide_grade_totals'),
        );
    }
}
