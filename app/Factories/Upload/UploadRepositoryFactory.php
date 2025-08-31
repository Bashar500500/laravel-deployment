<?php

namespace App\Factories\Upload;

use Illuminate\Contracts\Container\Container;
use App\Enums\Trait\ModelName;
use App\Repositories\Course\InstructorCourseRepository;
use App\Repositories\Course\CourseRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Group\StudentGroupRepository;
use App\Repositories\Group\InstructorGroupRepository;
use App\Repositories\LearningActivity\LearningActivityRepository;
use App\Repositories\LearningActivity\LearningActivityRepositoryInterface;
use App\Repositories\Section\SectionRepository;
use App\Repositories\Section\SectionRepositoryInterface;
use App\Repositories\Event\EventRepository;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\SubCategory\SubCategoryRepository;
use App\Repositories\SubCategory\SubCategoryRepositoryInterface;
use App\Repositories\Profile\StudentProfileRepository;
use App\Repositories\Profile\InstructorProfileRepository;
use App\Repositories\Profile\UserProfileRepositoryInterface;
use App\Repositories\Profile\AdminProfileRepository;
use App\Repositories\Profile\AdminProfileRepositoryInterface;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Assignment\AssignmentRepository;
use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Repositories\Wiki\WikiRepository;
use App\Repositories\Wiki\WikiRepositoryInterface;
use App\Repositories\InteractiveContent\InteractiveContentRepository;
use App\Repositories\InteractiveContent\InteractiveContentRepositoryInterface;
use App\Repositories\ReusableContent\ReusableContentRepository;
use App\Repositories\ReusableContent\ReusableContentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UploadRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(ModelName $name): AdminProfileRepositoryInterface|CategoryRepositoryInterface|CourseRepositoryInterface|GroupRepositoryInterface|LearningActivityRepositoryInterface|ProjectRepositoryInterface|SectionRepositoryInterface|EventRepositoryInterface|SubCategoryRepositoryInterface|UserProfileRepositoryInterface|AssignmentRepositoryInterface|WikiRepositoryInterface|InteractiveContentRepositoryInterface|ReusableContentRepositoryInterface
    {
        $role = Auth::user()->getRoleNames();
        return match ($name) {
            ModelName::Course => $this->container->make(InstructorCourseRepository::class),
            ModelName::Group => $role[0] == 'student' ?
                $this->container->make(StudentGroupRepository::class) :
                $this->container->make(InstructorGroupRepository::class),
            ModelName::LearningActivity => $this->container->make(LearningActivityRepository::class),
            ModelName::Section => $this->container->make(SectionRepository::class),
            ModelName::Event => $this->container->make(EventRepository::class),
            ModelName::Category => $this->container->make(CategoryRepository::class),
            ModelName::SubCategory => $this->container->make(SubCategoryRepository::class),
            ModelName::UserProfile => $role[0] == 'student' ?
                $this->container->make(StudentProfileRepository::class) :
                $this->container->make(InstructorProfileRepository::class),
            ModelName::AdminProfile => $this->container->make(AdminProfileRepository::class),
            ModelName::Project => $this->container->make(ProjectRepository::class),
            ModelName::Assignment => $this->container->make(AssignmentRepository::class),
            ModelName::Wiki => $this->container->make(WikiRepository::class),
            ModelName::InteractiveContent => $this->container->make(InteractiveContentRepository::class),
            ModelName::ReusableContent => $this->container->make(ReusableContentRepository::class),
        };
    }
}
