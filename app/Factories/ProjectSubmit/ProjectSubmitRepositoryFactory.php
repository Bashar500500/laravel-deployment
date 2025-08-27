<?php

namespace App\Factories\ProjectSubmit;

use Illuminate\Contracts\Container\Container;
use App\Repositories\ProjectSubmit\ProjectSubmitRepositoryInterface;
use App\Repositories\ProjectSubmit\InstructorProjectSubmitRepository;
use App\Repositories\ProjectSubmit\StudentProjectSubmitRepository;

class ProjectSubmitRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): ProjectSubmitRepositoryInterface
    {
        return match ($role) {
            'instructor' => $this->container->make(InstructorProjectSubmitRepository::class),
            'student' => $this->container->make(StudentProjectSubmitRepository::class),
        };
    }
}
