<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Course\CourseLanguage;
use App\Enums\Course\CourseLevel;
use App\Enums\Course\CourseStatus;
use App\Enums\Course\CourseAccessType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User\User;
use App\Models\Category\Category;
use App\Models\Group\Group;
use App\Models\Section\Section;
use App\Models\UserCourseGroup\UserCourseGroup;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\LearningActivity\LearningActivity;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ScheduleTiming\ScheduleTiming;
use App\Models\Event\Event;
use App\Models\Progress\Progress;
use App\Models\Project\Project;
use App\Models\Assessment\Assessment;
use App\Models\QuestionBank\QuestionBank;
use App\Models\Assignment\Assignment;
use App\Models\ChallengeCourse\ChallengeCourse;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Whiteboard\Whiteboard;
use App\Models\EnrollmentOption\EnrollmentOption;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;
use App\Models\ContentEngagement\ContentEngagement;
use App\Models\CourseReview\CourseReview;
use App\Models\ForumPost\ForumPost;
use App\Models\LearningRecommendation\LearningRecommendation;
use App\Models\MediaEngagement\MediaEngagement;
use App\Models\PageView\PageView;
use App\Models\Prerequisite\Prerequisite;
use App\Models\UserActivity\UserActivity;
use App\Models\UserCertificate\UserCertificate;
use App\Models\UserInteraction\UserInteraction;

class Course extends Model
{
    protected $fillable = [
        'instructor_id',
        'category_id',
        'name',
        'description',
        'language',
        'level',
        'timezone',
        'start_date',
        'end_date',
        'status',
        'duration',
        'estimated_duration_hours',
        'price',
        'code',
        'access_settings_access_type',
        'access_settings_price_hidden',
        'access_settings_is_secret',
        'access_settings_enrollment_limit_enabled',
        'access_settings_enrollment_limit_limit',
        'features_personalized_learning_paths',
        'features_certificate_requires_submission',
        'features_discussion_features_attach_files',
        'features_discussion_features_create_topics',
        'features_discussion_features_edit_replies',
        'features_student_groups',
        'features_is_featured',
        'features_show_progress_screen',
        'features_hide_grade_totals',
    ];

    protected $casts = [
        'language' => CourseLanguage::class,
        'level' => CourseLevel::class,
        'status' => CourseStatus::class,
        'access_type' => CourseAccessType::class,
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'course_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'course_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(UserCourseGroup::class, 'course_id');
    }

    public function learningActivities(): HasManyThrough
    {
        return $this->hasManyThrough(LearningActivity::class, Section::class,
            'course_id',
            'section_id',
            'id',
            'id'
        );
    }

    public function getCourseStudentCode( int $studentId, int $courseId): UserCourseGroup
    {
        return UserCourseGroup::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->select('student_code')->first();
    }

    public function scheduleTiming(): HasOne
    {
        return $this->hasOne(ScheduleTiming::class, 'course_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'course_id');
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Progress::class, 'course_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'course_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'course_id');
    }

    public function questionBank(): HasOne
    {
        return $this->hasOne(QuestionBank::class, 'course_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'course_id');
    }

    public function challengeCourses(): HasMany
    {
        return $this->hasMany(ChallengeCourse::class, 'course_id');
    }

    public function studentProjects(int $userId): Collection
    {
        $groupIds = UserCourseGroup::where('student_id', $userId)
            ->whereIn('group_id', $this->groups->pluck('id'))
            ->pluck('group_id');

        return $this->projects()
            ->where(function ($query) use ($userId, $groupIds) {
                $query->where('leader_id', $userId)
                    ->orWhereIn('group_id', $groupIds);
            })
            ->get();
    }

    public function whiteboards(): HasMany
    {
        return $this->hasMany(Whiteboard::class, 'course_id');
    }

    public function enrollmentOption(): HasOne
    {
        return $this->hasOne(EnrollmentOption::class, 'course_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function prerequisites(): MorphMany
    {
        return $this->morphMany(Prerequisite::class, 'prerequisiteable');
    }

    public function prerequisite(): MorphOne
    {
        return $this->morphOne(Prerequisite::class, 'prerequisiteable');
    }

    public function requireds(): MorphMany
    {
        return $this->morphMany(Prerequisite::class, 'requiredable');
    }

    public function required(): MorphOne
    {
        return $this->morphOne(Prerequisite::class, 'requiredable');
    }

    public function certificates(): MorphMany
    {
        return $this->morphMany(UserCertificate::class, 'certificateable');
    }

    public function certificate(): MorphOne
    {
        return $this->morphOne(UserCertificate::class, 'certificateable');
    }

    public function userActivities(): HasMany
    {
        return $this->hasMany(UserActivity::class, 'course_id');
    }

    public function forumPosts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'course_id');
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class, 'course_id');
    }

    public function userInteractions(): HasMany
    {
        return $this->hasMany(UserInteraction::class, 'course_id');
    }

    public function contentEngagements(): HasMany
    {
        return $this->hasMany(ContentEngagement::class, 'course_id');
    }

    public function mediaEngagements(): HasMany
    {
        return $this->hasMany(MediaEngagement::class, 'course_id');
    }

    public function learningRecommendations(): HasMany
    {
        return $this->hasMany(LearningRecommendation::class, 'course_id');
    }

    public function courseReviews(): HasMany
    {
        return $this->hasMany(CourseReview::class, 'course_id');
    }
}
