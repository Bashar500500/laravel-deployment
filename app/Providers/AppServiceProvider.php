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
use URL;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production'))
        {
            URL::forceScheme('https');
        }
    }
}
