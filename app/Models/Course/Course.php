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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

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
        'price',
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

    public function getCourseStudentCode(int $courseId, int $studentId): UserCourseGroup
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

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }
}
