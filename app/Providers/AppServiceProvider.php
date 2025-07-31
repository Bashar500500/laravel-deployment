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
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Group\GroupRepository;
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
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\AdminRepository;
use App\Repositories\User\AdminRepositoryInterface;
use App\Models\User\User;
use App\Repositories\Profile\UserProfileRepository;
use App\Repositories\Profile\UserProfileRepositoryInterface;
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
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Project\ProjectRepository;
use App\Models\Project\Project;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\Ticket\TicketRepository;
use App\Models\Ticket\Ticket;
use App\Repositories\CommunityAccess\CommunityAccessRepositoryInterface;
use App\Repositories\CommunityAccess\CommunityAccessRepository;
use App\Models\CommunityAccess\CommunityAccess;
use App\Repositories\Assessment\AssessmentRepositoryInterface;
use App\Repositories\Assessment\AssessmentRepository;
use App\Models\Assessment\Assessment;
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
use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Repositories\Assignment\AssignmentRepository;
use App\Models\Assignment\Assignment;
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
use Illuminate\Support\Facades\Gate;
use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\Models\Course\Course;
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
use App\Policies\Ticket\TicketPolicy;
use App\Policies\TimeLimit\TimeLimitPolicy;
use App\Policies\User\AdminAndUserPolicy;

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

        $this->app->bind(GroupRepositoryInterface::class, function (Container $app) {
            return new GroupRepository($app->make(Group::class),
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

        $this->app->bind(UserRepositoryInterface::class, function (Container $app) {
            return new UserRepository($app->make(User::class),
            );
        });

        $this->app->bind(AdminRepositoryInterface::class, function (Container $app) {
            return new AdminRepository($app->make(User::class),
            );
        });

        $this->app->bind(UserProfileRepositoryInterface::class, function (Container $app) {
            return new UserProfileRepository($app->make(Profile::class),
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

        $this->app->bind(ProjectRepositoryInterface::class, function (Container $app) {
            return new ProjectRepository($app->make(Project::class),
            );
        });

        $this->app->bind(TicketRepositoryInterface::class, function (Container $app) {
            return new TicketRepository($app->make(Ticket::class),
            );
        });

        $this->app->bind(CommunityAccessRepositoryInterface::class, function (Container $app) {
            return new CommunityAccessRepository($app->make(CommunityAccess::class),
            );
        });

        $this->app->bind(AssessmentRepositoryInterface::class, function (Container $app) {
            return new AssessmentRepository($app->make(Assessment::class),
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

        $this->app->bind(AssignmentRepositoryInterface::class, function (Container $app) {
            return new AssignmentRepository($app->make(Assignment::class),
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
        Ticket::class => TicketPolicy::class,
        TimeLimit::class => TimeLimitPolicy::class,
        User::class => AdminAndUserPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
