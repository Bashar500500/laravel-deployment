<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Profile\Profile;
use App\Models\Message\Message;
use App\Models\Reply\Reply;
use App\Models\Chat\DirectChat;
use App\Models\UserCourseGroup\UserCourseGroup;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Course\Course;
use App\Models\Group\Group;
use App\Models\TeachingHour\TeachingHour;
use App\Models\ScheduleTiming\ScheduleTiming;
use App\Models\Grade\Grade;
use App\Models\Progress\Progress;
use App\Models\Attendance\Attendance;
use App\Models\SectionCompletion\SectionCompletion;
use App\Models\Email\Email;
use App\Models\Project\Project;
use App\Models\SupportTicket\SupportTicket;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\Models\TimeLimit\TimeLimit;
use App\Models\Challenge\Challenge;
use App\Models\Badge\Badge;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\Models\ChallengeUser\ChallengeUser;
use App\Models\ContentEngagement\ContentEngagement;
use App\Models\CourseReview\CourseReview;
use App\Models\UserAward\UserAward;
use App\Models\UserRule\UserRule;
use App\Models\Holiday\Holiday;
use App\Models\Leave\Leave;
use App\Models\Section\Section;
use App\Models\Event\Event;
use App\Models\ForumPost\ForumPost;
use App\Models\GradeAppeal\GradeAppeal;
use App\Models\InstructorStudent\InstructorStudent;
use App\Models\InteractiveContent\InteractiveContent;
use App\Models\LearningGap\LearningGap;
use App\Models\MediaEngagement\MediaEngagement;
use App\Models\Rubric\Rubric;
use App\Models\Wiki\Wiki;
use App\Models\WikiComment\WikiComment;
use App\Models\WikiRating\WikiRating;
use App\Models\Prerequisite\Prerequisite;
use App\Models\UserCertificate\UserCertificate;
use App\Models\Whiteboard\Whiteboard;
use App\Models\Notification\Notification;
use App\Models\PageView\PageView;
use App\Models\ReusableContent\ReusableContent;
use App\Models\UserActivity\UserActivity;
use App\Models\UserInteraction\UserInteraction;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guard_name = 'api';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function directChats(): HasMany
    {
        return $this->hasMany(DirectChat::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class, 'user_id');
    }

    public function userCourseGroups(): HasMany
    {
        return $this->hasMany(UserCourseGroup::class, 'student_id');
    }

    public function enrolledCourses(): HasManyThrough
    {
        return $this->hasManyThrough(Course::class, UserCourseGroup::class,
            'student_id',
            'id',
            'id',
            'course_id'
        );
    }

    public function ownedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function groups(): HasManyThrough
    {
        return $this->hasManyThrough(Group::class, UserCourseGroup::class,
            'student_id',
            'id',
            'id',
            'group_id'
        );
    }

    public function teachingHours(): HasOne
    {
        return $this->hasOne(TeachingHour::class, 'instructor_id');
    }

    public function scheduleTimings(): HasMany
    {
        return $this->hasMany(ScheduleTiming::class, 'instructor_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Progress::class, 'student_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function sectionCompletions(): HasMany
    {
        return $this->hasMany(SectionCompletion::class, 'student_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'leader_id');
    }

    public function supporttickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    public function assessmentSubmits(): HasMany
    {
        return $this->hasMany(AssessmentSubmit::class, 'student_id');
    }

    public function timeLimits(): HasMany
    {
        return $this->hasMany(TimeLimit::class, 'instructor_id');
    }

    public function challenges(): HasMany
    {
        return $this->hasMany(Challenge::class, 'instructor_id');
    }

    public function badges(): HasMany
    {
        return $this->hasMany(Badge::class, 'instructor_id');
    }

    public function assignmentSubmits(): HasMany
    {
        return $this->hasMany(AssignmentSubmit::class, 'student_id');
    }

    public function challengeUsers(): HasMany
    {
        return $this->hasMany(ChallengeUser::class, 'student_id');
    }

    public function userAwards(): HasMany
    {
        return $this->hasMany(UserAward::class, 'student_id');
    }

    public function userRules(): HasMany
    {
        return $this->hasMany(UserRule::class, 'student_id');
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class, 'instructor_id');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'instructor_id');
    }

    public function sections(): HasManyThrough
    {
        return $this->hasManyThrough(Section::class, Course::class,
            'instructor_id',
            'id',
            'course_id',
            'id'
        );
    }

    public function events(): HasManyThrough
    {
        return $this->hasManyThrough(Event::class, Course::class,
            'instructor_id',
            'id',
            'course_id',
            'id'
        );
    }

    public function instructorStudentsForInstructor(): HasOne
    {
        return $this->hasOne(InstructorStudent::class, 'instructor_id');
    }

    public function instructorStudentsForStudent(): HasOne
    {
        return $this->hasOne(InstructorStudent::class, 'student_id');
    }

    public function rubrics(): HasMany
    {
        return $this->hasMany(Rubric::class, 'instructor_id');
    }

    public function wikis(): HasMany
    {
        return $this->hasMany(Wiki::class, 'user_id');
    }

    public function wikiComments(): HasMany
    {
        return $this->hasMany(WikiComment::class, 'user_id');
    }

    public function wikiRatings(): HasMany
    {
        return $this->hasMany(WikiRating::class, 'user_id');
    }

    public function prerequisites(): HasMany
    {
        return $this->hasMany(Prerequisite::class, 'instructor_id');
    }

    public function userCertificates(): HasMany
    {
        return $this->hasMany(UserCertificate::class, 'student_id');
    }

    public function whiteboards(): HasMany
    {
        return $this->hasMany(Whiteboard::class, 'instructor_id');
    }

    public function interactiveContents(): HasMany
    {
        return $this->hasMany(InteractiveContent::class, 'instructor_id');
    }

    public function reusableContents(): HasMany
    {
        return $this->hasMany(ReusableContent::class, 'instructor_id');
    }

    public function gradeAppeals(): HasMany
    {
        return $this->hasMany(GradeAppeal::class, 'student_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function userActivities(): HasMany
    {
        return $this->hasMany(UserActivity::class, 'student_id');
    }

    public function forumPosts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'student_id');
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class, 'student_id');
    }

    public function userInteractions(): HasMany
    {
        return $this->hasMany(UserInteraction::class, 'student_id');
    }

    public function contentEngagements(): HasMany
    {
        return $this->hasMany(ContentEngagement::class, 'student_id');
    }

    public function mediaEngagements(): HasMany
    {
        return $this->hasMany(MediaEngagement::class, 'student_id');
    }

    public function learningGaps(): HasMany
    {
        return $this->hasMany(LearningGap::class, 'student_id');
    }

    public function courseReviews(): HasMany
    {
        return $this->hasMany(CourseReview::class, 'student_id');
    }

    // public function notifications(): MorphMany
    // {
    //     return $this->morphMany(Notification::class, 'notificationable');
    // }

    // public function notification(): MorphOne
    // {
    //     return $this->morphOne(Notification::class, 'notificationable');
    // }
}
