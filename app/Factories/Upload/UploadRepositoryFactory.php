<?php

namespace App\Factories\Upload;

use Illuminate\Contracts\Container\Container;
use App\Enums\Trait\ModelName;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Group\GroupRepository;
use App\Repositories\LearningActivity\LearningActivityRepository;
use App\Repositories\Section\SectionRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\SubCategory\SubCategoryRepository;
use App\Repositories\Profile\UserProfileRepository;
use App\Repositories\Profile\AdminProfileRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Project\ProjectRepository;

class UploadRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(ModelName $name): AdminProfileRepository|CategoryRepository|CourseRepository|GroupRepository|LearningActivityRepository|ProjectRepository|QuestionRepository|SectionRepository|SubCategoryRepository|UserProfileRepository
    {
        return match ($name) {
            ModelName::Course => $this->container->make(CourseRepository::class),
            ModelName::Group => $this->container->make(GroupRepository::class),
            ModelName::LearningActivity => $this->container->make(LearningActivityRepository::class),
            ModelName::Section => $this->container->make(SectionRepository::class),
            ModelName::Category => $this->container->make(CategoryRepository::class),
            ModelName::SubCategory => $this->container->make(SubCategoryRepository::class),
            ModelName::UserProfile => $this->container->make(UserProfileRepository::class),
            ModelName::AdminProfile => $this->container->make(AdminProfileRepository::class),
            ModelName::Question => $this->container->make(QuestionRepository::class),
            ModelName::Project => $this->container->make(ProjectRepository::class),
        };
    }
}
