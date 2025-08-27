<?php

namespace App\Factories\Project;

use Illuminate\Contracts\Container\Container;
use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Project\InstructorProjectRepository;
use App\Repositories\Project\StudentProjectRepository;

class ProjectRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): ProjectRepositoryInterface
    {
        return match ($role) {
            'instructor' => $this->container->make(InstructorProjectRepository::class),
            'student' => $this->container->make(StudentProjectRepository::class),
        };
    }
}
