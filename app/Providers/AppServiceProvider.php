<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Message\MessageRepository;
use App\Models\Message\Message;
use App\Repositories\Reply\ReplyRepositoryInterface;
use App\Repositories\Reply\ReplyRepository;
use App\Models\Reply\Reply;
use App\Models\Group\Group;
use App\Repositories\LearningActivity\LearningActivityRepositoryInterface;
use App\Repositories\LearningActivity\LearningActivityRepository;
use App\Models\LearningActivity\LearningActivity;
use App\Repositories\Section\SectionRepositoryInterface;
use App\Repositories\Section\SectionRepository;
use App\Models\Section\Section;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Models\Category\Category;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use App\Repositories\SubCategory\SubCategoryRepositoryInterface;
use App\Repositories\SubCategory\SubCategoryRepository;
use App\Models\SubCategory\SubCategory;
use App\Repositories\User\AdminRepository;
use App\Repositories\User\AdminRepositoryInterface;
use App\Models\User\User;
use App\Repositories\Profile\AdminProfileRepository;
use App\Repositories\Profile\AdminProfileRepositoryInterface;
use App\Models\Profile\Profile;
use App\Repositories\Holiday\HolidayRepositoryInterface;
use App\Repositories\Holiday\HolidayRepository;
use App\Models\Holiday\Holiday;
use App\Repositories\Leave\LeaveRepositoryInterface;
use App\Repositories\Leave\LeaveRepository;
use App\Models\Leave\Leave;
use App\Repositories\Policy\PolicyRepositoryInterface;
use App\Repositories\Policy\PolicyRepository;
use App\Models\Policy\Policy;
use App\Repositories\TeachingHour\TeachingHourRepositoryInterface;
use App\Repositories\TeachingHour\TeachingHourRepository;
use App\Models\TeachingHour\TeachingHour;
use App\Repositories\ScheduleTiming\ScheduleTimingRepositoryInterface;
use App\Repositories\ScheduleTiming\ScheduleTimingRepository;
use App\Models\ScheduleTiming\ScheduleTiming;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\Event\EventRepository;
use App\Models\Event\Event;
use App\Repositories\Grade\GradeRepositoryInterface;
use App\Repositories\Grade\GradeRepository;
use App\Models\Grade\Grade;
use App\Repositories\Progress\ProgressRepositoryInterface;
use App\Repositories\Progress\ProgressRepository;
use App\Models\Progress\Progress;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Repositories\Attendance\AttendanceRepository;
use App\Models\Attendance\Attendance;
use App\Repositories\Auth\RegisterRepositoryInterface;
use App\Repositories\Auth\RegisterRepository;
use App\Repositories\Auth\PasswordResetCodeRepositoryInterface;
use App\Repositories\Auth\PasswordResetCodeRepository;
use App\Models\Auth\PasswordResetCode;
use App\Repositories\CommunityAccess\CommunityAccessRepositoryInterface;
use App\Repositories\CommunityAccess\CommunityAccessRepository;
use App\Models\CommunityAccess\CommunityAccess;
use App\Repositories\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionRepositoryInterface;
use App\Repositories\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionRepository;
use App\Models\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestion;
use App\Repositories\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionRepositoryInterface;
use App\Repositories\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionRepository;
use App\Models\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestion;
use App\Repositories\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionRepositoryInterface;
use App\Repositories\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionRepository;
use App\Models\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestion;
use App\Repositories\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionRepositoryInterface;
use App\Repositories\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionRepository;
use App\Models\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestion;
use App\Repositories\QuestionBank\QuestionBankRepositoryInterface;
use App\Repositories\QuestionBank\QuestionBankRepository;
use App\Models\QuestionBank\QuestionBank;
use App\Repositories\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionRepositoryInterface;
use App\Repositories\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionRepository;
use App\Models\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestion;
use App\Repositories\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionRepositoryInterface;
use App\Repositories\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionRepository;
use App\Models\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestion;
use App\Repositories\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionRepositoryInterface;
use App\Repositories\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionRepository;
use App\Models\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestion;
use App\Repositories\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionRepositoryInterface;
use App\Repositories\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionRepository;
use App\Models\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestion;
use App\Repositories\TimeLimit\TimeLimitRepositoryInterface;
use App\Repositories\TimeLimit\TimeLimitRepository;
use App\Models\TimeLimit\TimeLimit;
use App\Repositories\Challenge\ChallengeRepositoryInterface;
use App\Repositories\Challenge\ChallengeRepository;
use App\Models\Challenge\Challenge;
use App\Repositories\Rule\RuleRepositoryInterface;
use App\Repositories\Rule\RuleRepository;
use App\Models\Rule\Rule;
use App\Repositories\Badge\BadgeRepositoryInterface;
use App\Repositories\Badge\BadgeRepository;
use App\Models\Badge\Badge;
use App\Repositories\Plagiarism\PlagiarismRepositoryInterface;
use App\Repositories\Plagiarism\PlagiarismRepository;
use App\Models\Plagiarism\Plagiarism;
use App\Repositories\Prerequisite\PrerequisiteRepositoryInterface;
use App\Repositories\Prerequisite\PrerequisiteRepository;
use App\Models\Prerequisite\Prerequisite;
use App\Repositories\Rubric\RubricRepositoryInterface;
use App\Repositories\Rubric\RubricRepository;
use App\Models\Rubric\Rubric;
use App\Repositories\Wiki\WikiRepositoryInterface;
use App\Repositories\Wiki\WikiRepository;
use App\Models\Wiki\Wiki;
use App\Repositories\Certificate\CertificateRepositoryInterface;
use App\Repositories\Certificate\CertificateRepository;
use App\Models\Certificate\Certificate;
use App\Repositories\CertificateTemplate\CertificateTemplateRepositoryInterface;
use App\Repositories\CertificateTemplate\CertificateTemplateRepository;
use App\Models\CertificateTemplate\CertificateTemplate;
use App\Repositories\EnrollmentOption\EnrollmentOptionRepositoryInterface;
use App\Repositories\EnrollmentOption\EnrollmentOptionRepository;
use App\Models\EnrollmentOption\EnrollmentOption;
use App\Repositories\Whiteboard\WhiteboardRepositoryInterface;
use App\Repositories\Whiteboard\WhiteboardRepository;
use App\Models\Whiteboard\Whiteboard;
use App\Repositories\InteractiveContent\InteractiveContentRepositoryInterface;
use App\Repositories\InteractiveContent\InteractiveContentRepository;
use App\Models\InteractiveContent\InteractiveContent;
use App\Repositories\ReusableContent\ReusableContentRepositoryInterface;
use App\Repositories\ReusableContent\ReusableContentRepository;
use App\Models\ReusableContent\ReusableContent;
// use App\Repositories\WikiComment\WikiCommentRepositoryInterface;
// use App\Repositories\WikiComment\WikiCommentRepository;
// use App\Models\WikiComment\WikiComment;
// use App\Repositories\WikiRating\WikiRatingRepositoryInterface;
// use App\Repositories\WikiRating\WikiRatingRepository;
// use App\Models\WikiRating\WikiRating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Gate;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\Models\ProjectSubmit\ProjectSubmit;
use App\Models\Course\Course;
use App\Models\Assessment\Assessment;
use App\Models\Assignment\Assignment;
use App\Models\Project\Project;
use App\Models\SupportTicket\SupportTicket;
use App\Policies\Assessment\AssessmentPolicy;
use App\Policies\AssessmentFillInBlankQuestion\AssessmentFillInBlankQuestionPolicy;
use App\Policies\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestionPolicy;
use App\Policies\AssessmentShortAnswerQuestion\AssessmentShortAnswerQuestionPolicy;
use App\Policies\AssessmentSubmit\AssessmentSubmitPolicy;
use App\Policies\AssessmentTrueOrFalseQuestion\AssessmentTrueOrFalseQuestionPolicy;
use App\Policies\Assignment\AssignmentPolicy;
use App\Policies\AssignmentSubmit\AssignmentSubmitPolicy;
use App\Policies\Attendance\AttendancePolicy;
use App\Policies\Badge\BadgePolicy;
use App\Policies\Category\CategoryPolicy;
use App\Policies\Challenge\ChallengePolicy;
use App\Policies\CommunityAccess\CommunityAccessPolicy;
use App\Policies\Course\CoursePolicy;
use App\Policies\Event\EventPolicy;
use App\Policies\Grade\GradePolicy;
use App\Policies\Group\GroupPolicy;
use App\Policies\Holiday\HolidayPolicy;
use App\Policies\LearningActivity\LearningActivityPolicy;
use App\Policies\Leave\LeavePolicy;
use App\Policies\Policy\PolicyPolicy;
use App\Policies\Profile\AdminAndUserProfilePolicy;
use App\Policies\Progress\ProgressPolicy;
use App\Policies\Project\ProjectPolicy;
use App\Policies\QuestionBank\QuestionBankPolicy;
use App\Policies\QuestionBankFillInBlankQuestion\QuestionBankFillInBlankQuestionPolicy;
use App\Policies\QuestionBankMultipleTypeQuestion\QuestionBankMultipleTypeQuestionPolicy;
use App\Policies\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestionPolicy;
use App\Policies\QuestionBankTrueOrFalseQuestion\QuestionBankTrueOrFalseQuestionPolicy;
use App\Policies\Rule\RulePolicy;
use App\Policies\ScheduleTiming\ScheduleTimingPolicy;
use App\Policies\Section\SectionPolicy;
use App\Policies\SubCategory\SubCategoryPolicy;
use App\Policies\TeachingHour\TeachingHourPolicy;
use App\Policies\SupportTicket\SupportTicketPolicy;
use App\Policies\TimeLimit\TimeLimitPolicy;
use App\Policies\User\AdminAndUserPolicy;
use App\Policies\Plagiarism\PlagiarismPolicy;
use App\Policies\ProjectSubmit\ProjectSubmitPolicy;
use App\Policies\Prerequisite\PrerequisitePolicy;
use App\Policies\Rubric\RubricPolicy;
use App\Policies\Wiki\WikiPolicy;
use App\Policies\WikiComment\WikiCommentPolicy;
use App\Policies\WikiRating\WikiRatingPolicy;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MessageRepositoryInterface::class, function (Container $app) {
            return new MessageRepository($app->make(Message::class),
            );
        });

        $this->app->bind(ReplyRepositoryInterface::class, function (Container $app) {
            return new ReplyRepository($app->make(Reply::class),
            );
        });

        $this->app->bind(LearningActivityRepositoryInterface::class, function (Container $app) {
            return new LearningActivityRepository($app->make(LearningActivity::class),
            );
        });

        $this->app->bind(SectionRepositoryInterface::class, function (Container $app) {
            return new SectionRepository($app->make(Section::class),
            );
        });

        $this->app->bind(CategoryRepositoryInterface::class, function (Container $app) {
            return new CategoryRepository($app->make(Category::class),
            );
        });

        $this->app->bind(SubCategoryRepositoryInterface::class, function (Container $app) {
            return new SubCategoryRepository($app->make(SubCategory::class),
            );
        });

        $this->app->bind(AdminRepositoryInterface::class, function (Container $app) {
            return new AdminRepository($app->make(User::class),
            );
        });

        $this->app->bind(AdminProfileRepositoryInterface::class, function (Container $app) {
            return new AdminProfileRepository($app->make(Profile::class),
            );
        });

        $this->app->bind(PermissionRepositoryInterface::class, function (Container $app) {
            return new PermissionRepository($app->make(Permission::class),
            );
        });

        $this->app->bind(HolidayRepositoryInterface::class, function (Container $app) {
            return new HolidayRepository($app->make(Holiday::class),
            );
        });

        $this->app->bind(LeaveRepositoryInterface::class, function (Container $app) {
            return new LeaveRepository($app->make(Leave::class),
            );
        });

        $this->app->bind(PolicyRepositoryInterface::class, function (Container $app) {
            return new PolicyRepository($app->make(Policy::class),
            );
        });

        $this->app->bind(TeachingHourRepositoryInterface::class, function (Container $app) {
            return new TeachingHourRepository($app->make(TeachingHour::class),
            );
        });

        $this->app->bind(ScheduleTimingRepositoryInterface::class, function (Container $app) {
            return new ScheduleTimingRepository($app->make(ScheduleTiming::class),
            );
        });

        $this->app->bind(EventRepositoryInterface::class, function (Container $app) {
            return new EventRepository($app->make(Event::class),
            );
        });

        $this->app->bind(GradeRepositoryInterface::class, function (Container $app) {
            return new GradeRepository($app->make(Grade::class),
            );
        });

        $this->app->bind(ProgressRepositoryInterface::class, function (Container $app) {
            return new ProgressRepository($app->make(Progress::class),
            );
        });

        $this->app->bind(AttendanceRepositoryInterface::class, function (Container $app) {
            return new AttendanceRepository($app->make(Attendance::class),
            );
        });

        $this->app->bind(RegisterRepositoryInterface::class, function (Container $app) {
            return new RegisterRepository($app->make(User::class),
            );
        });

        $this->app->bind(PasswordResetCodeRepositoryInterface::class, function (Container $app) {
            return new PasswordResetCodeRepository($app->make(PasswordResetCode::class),
            );
        });

        $this->app->bind(CommunityAccessRepositoryInterface::class, function (Container $app) {
            return new CommunityAccessRepository($app->make(CommunityAccess::class),
            );
        });

        $this->app->bind(AssessmentFillInBlankQuestionRepositoryInterface::class, function (Container $app) {
            return new AssessmentFillInBlankQuestionRepository($app->make(AssessmentFillInBlankQuestion::class),
            );
        });

        $this->app->bind(AssessmentMultipleTypeQuestionRepositoryInterface::class, function (Container $app) {
            return new AssessmentMultipleTypeQuestionRepository($app->make(AssessmentMultipleTypeQuestion::class),
            );
        });

        $this->app->bind(AssessmentShortAnswerQuestionRepositoryInterface::class, function (Container $app) {
            return new AssessmentShortAnswerQuestionRepository($app->make(AssessmentShortAnswerQuestion::class),
            );
        });

        $this->app->bind(AssessmentTrueOrFalseQuestionRepositoryInterface::class, function (Container $app) {
            return new AssessmentTrueOrFalseQuestionRepository($app->make(AssessmentTrueOrFalseQuestion::class),
            );
        });

        $this->app->bind(QuestionBankRepositoryInterface::class, function (Container $app) {
            return new QuestionBankRepository($app->make(QuestionBank::class),
            );
        });

        $this->app->bind(QuestionBankFillInBlankQuestionRepositoryInterface::class, function (Container $app) {
            return new QuestionBankFillInBlankQuestionRepository($app->make(QuestionBankFillInBlankQuestion::class),
            );
        });

        $this->app->bind(QuestionBankMultipleTypeQuestionRepositoryInterface::class, function (Container $app) {
            return new QuestionBankMultipleTypeQuestionRepository($app->make(QuestionBankMultipleTypeQuestion::class),
            );
        });

        $this->app->bind(QuestionBankShortAnswerQuestionRepositoryInterface::class, function (Container $app) {
            return new QuestionBankShortAnswerQuestionRepository($app->make(QuestionBankShortAnswerQuestion::class),
            );
        });

        $this->app->bind(QuestionBankTrueOrFalseQuestionRepositoryInterface::class, function (Container $app) {
            return new QuestionBankTrueOrFalseQuestionRepository($app->make(QuestionBankTrueOrFalseQuestion::class),
            );
        });

        $this->app->bind(TimeLimitRepositoryInterface::class, function (Container $app) {
            return new TimeLimitRepository($app->make(TimeLimit::class),
            );
        });

        $this->app->bind(ChallengeRepositoryInterface::class, function (Container $app) {
            return new ChallengeRepository($app->make(Challenge::class),
            );
        });

        $this->app->bind(RuleRepositoryInterface::class, function (Container $app) {
            return new RuleRepository($app->make(Rule::class),
            );
        });

        $this->app->bind(BadgeRepositoryInterface::class, function (Container $app) {
            return new BadgeRepository($app->make(Badge::class),
            );
        });

        $this->app->bind(PlagiarismRepositoryInterface::class, function (Container $app) {
            return new PlagiarismRepository($app->make(Plagiarism::class),
            );
        });

        $this->app->bind(PrerequisiteRepositoryInterface::class, function (Container $app) {
            return new PrerequisiteRepository($app->make(Prerequisite::class),
            );
        });

        $this->app->bind(RubricRepositoryInterface::class, function (Container $app) {
            return new RubricRepository($app->make(Rubric::class),
            );
        });

        $this->app->bind(WikiRepositoryInterface::class, function (Container $app) {
            return new WikiRepository($app->make(Wiki::class),
            );
        });

        // $this->app->bind(WikiCommentRepositoryInterface::class, function (Container $app) {
        //     return new WikiCommentRepository($app->make(WikiComment::class),
        //     );
        // });

        // $this->app->bind(WikiRatingRepositoryInterface::class, function (Container $app) {
        //     return new WikiRatingRepository($app->make(WikiRating::class),
        //     );
        // });

        $this->app->bind(CertificateRepositoryInterface::class, function (Container $app) {
            return new CertificateRepository($app->make(Certificate::class),
            );
        });

        $this->app->bind(CertificateTemplateRepositoryInterface::class, function (Container $app) {
            return new CertificateTemplateRepository($app->make(CertificateTemplate::class),
            );
        });

        $this->app->bind(EnrollmentOptionRepositoryInterface::class, function (Container $app) {
            return new EnrollmentOptionRepository($app->make(EnrollmentOption::class),
            );
        });

        $this->app->bind(WhiteboardRepositoryInterface::class, function (Container $app) {
            return new WhiteboardRepository($app->make(Whiteboard::class),
            );
        });

        $this->app->bind(InteractiveContentRepositoryInterface::class, function (Container $app) {
            return new InteractiveContentRepository($app->make(InteractiveContent::class),
            );
        });

        $this->app->bind(ReusableContentRepositoryInterface::class, function (Container $app) {
            return new ReusableContentRepository($app->make(ReusableContent::class),
            );
        });
    }

    protected $policies = [
        Assessment::class => AssessmentPolicy::class,
        AssessmentFillInBlankQuestion::class => AssessmentFillInBlankQuestionPolicy::class,
        AssessmentMultipleTypeQuestion::class => AssessmentMultipleTypeQuestionPolicy::class,
        AssessmentShortAnswerQuestion::class => AssessmentShortAnswerQuestionPolicy::class,
        AssessmentSubmit::class => AssessmentSubmitPolicy::class,
        AssessmentTrueOrFalseQuestion::class => AssessmentTrueOrFalseQuestionPolicy::class,
        Assignment::class => AssignmentPolicy::class,
        AssignmentSubmit::class => AssignmentSubmitPolicy::class,
        Attendance::class => AttendancePolicy::class,
        Badge::class => BadgePolicy::class,
        Category::class => CategoryPolicy::class,
        Challenge::class => ChallengePolicy::class,
        CommunityAccess::class => CommunityAccessPolicy::class,
        Course::class => CoursePolicy::class,
        Event::class => EventPolicy::class,
        Grade::class => GradePolicy::class,
        Group::class => GroupPolicy::class,
        Holiday::class => HolidayPolicy::class,
        LearningActivity::class => LearningActivityPolicy::class,
        Leave::class => LeavePolicy::class,
        Policy::class => PolicyPolicy::class,
        Profile::class => AdminAndUserProfilePolicy::class,
        Progress::class => ProgressPolicy::class,
        Project::class => ProjectPolicy::class,
        QuestionBank::class => QuestionBankPolicy::class,
        QuestionBankFillInBlankQuestion::class => QuestionBankFillInBlankQuestionPolicy::class,
        QuestionBankMultipleTypeQuestion::class => QuestionBankMultipleTypeQuestionPolicy::class,
        QuestionBankShortAnswerQuestion::class => QuestionBankShortAnswerQuestionPolicy::class,
        QuestionBankTrueOrFalseQuestion::class => QuestionBankTrueOrFalseQuestionPolicy::class,
        Rule::class => RulePolicy::class,
        ScheduleTiming::class => ScheduleTimingPolicy::class,
        Section::class => SectionPolicy::class,
        SubCategory::class => SubCategoryPolicy::class,
        TeachingHour::class => TeachingHourPolicy::class,
        SupportTicket::class => SupportTicketPolicy::class,
        TimeLimit::class => TimeLimitPolicy::class,
        User::class => AdminAndUserPolicy::class,
        Plagiarism::class => PlagiarismPolicy::class,
        Prerequisite::class => PrerequisitePolicy::class,
        ProjectSubmit::class => ProjectSubmitPolicy::class,
        Rubric::class => RubricPolicy::class,
        Wiki::class => WikiPolicy::class,
        // WikiComment::class => WikiCommentPolicy::class,
        // WikiRating::class => WikiRatingPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Queue::looping(function () {
        //     DB::reconnect();
        // });

        // RateLimiter::for('ip-limit', function (Request $request) {
        //     return Limit::perSecond(20)->by($request->user()?->id ?: $request->ip());
        // });

        RateLimiter::for('ip-limit', function (Request $request) {
            // return Limit::perSecond(20)->by($request->ip())->response(function () {
            return Limit::perMinute(100)->by($request->ip())->response(function () {
                return response()->json(['message' => 'Rate limit exceeded'], 429);
            });
        });

        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
