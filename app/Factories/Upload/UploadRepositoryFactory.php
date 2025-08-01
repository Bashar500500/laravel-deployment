<?php

namespace App\Factories\Upload;

use Illuminate\Contracts\Container\Container;
use App\Enums\Trait\ModelName;
use App\Repositories\Course\InstructorCourseRepository;
use App\Repositories\Course\CourseRepositoryInterface;
use App\Repositories\Group\GroupRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\LearningActivity\LearningActivityRepository;
use App\Repositories\LearningActivity\LearningActivityRepositoryInterface;
use App\Repositories\Section\SectionRepository;
use App\Repositories\Section\SectionRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\SubCategory\SubCategoryRepository;
use App\Repositories\SubCategory\SubCategoryRepositoryInterface;
use App\Repositories\Profile\UserProfileRepository;
use App\Repositories\Profile\UserProfileRepositoryInterface;
use App\Repositories\Profile\AdminProfileRepository;
use App\Repositories\Profile\AdminProfileRepositoryInterface;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Project\ProjectRepositoryInterface;

class UploadRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(ModelName $name): AdminProfileRepositoryInterface|CategoryRepositoryInterface|CourseRepositoryInterface|GroupRepositoryInterface|LearningActivityRepositoryInterface|ProjectRepositoryInterface|SectionRepositoryInterface|SubCategoryRepositoryInterface|UserProfileRepositoryInterface
    {
        return match ($name) {
            ModelName::Course => $this->container->make(InstructorCourseRepository::class),
            ModelName::Group => $this->container->make(GroupRepository::class),
            ModelName::LearningActivity => $this->container->make(LearningActivityRepository::class),
            ModelName::Section => $this->container->make(SectionRepository::class),
            ModelName::Category => $this->container->make(CategoryRepository::class),
            ModelName::SubCategory => $this->container->make(SubCategoryRepository::class),
            ModelName::UserProfile => $this->container->make(UserProfileRepository::class),
            ModelName::AdminProfile => $this->container->make(AdminProfileRepository::class),
            ModelName::Project => $this->container->make(ProjectRepository::class),
        };
    }
}
