<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseFeatureResource extends JsonResource
{
    public static function makeJson(
        CourseResource $courseResource,
    ): array
    {
        return [
            'personalizedLearningPaths' => $courseResource->features_personalized_learning_paths == 0 ? 'false' : 'true',
            'certificateRequiresSubmission' => $courseResource->features_certificate_requires_submission == 0 ? 'false' : 'true',
            'discussionFeatures' => CourseDiscussionFeatureResource::makeJson($courseResource),
            'studentGroups' => $courseResource->features_student_groups == 0 ? 'false' : 'true',
            'isFeatured' => $courseResource->features_is_featured == 0 ? 'false' : 'true',
            'showProgressScreen' => $courseResource->features_show_progress_screen == 0 ? 'false' : 'true',
            'hideGradeTotals' => $courseResource->features_hide_grade_totals == 0 ? 'false' : 'true',
        ];
    }
}
