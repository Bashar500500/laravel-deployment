<?php

namespace App\Factories\AssignmentSubmit;

use Illuminate\Contracts\Container\Container;
use App\Repositories\AssignmentSubmit\AssignmentSubmitRepositoryInterface;
use App\Repositories\AssignmentSubmit\InstructorAssignmentSubmitRepository;
use App\Repositories\AssignmentSubmit\StudentAssignmentSubmitRepository;

class AssignmentSubmitRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): AssignmentSubmitRepositoryInterface
    {
        return match ($role) {
            'instructor' => $this->container->make(InstructorAssignmentSubmitRepository::class),
            'student' => $this->container->make(StudentAssignmentSubmitRepository::class),
        };
    }
}
