<?php

namespace App\Factories\Assignment;

use Illuminate\Contracts\Container\Container;
use App\Repositories\Assignment\AssignmentRepositoryInterface;
use App\Repositories\Assignment\InstructorAssignmentRepository;
use App\Repositories\Assignment\StudentAssignmentRepository;

class AssignmentRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): AssignmentRepositoryInterface
    {
        return match ($role) {
            'instructor' => $this->container->make(InstructorAssignmentRepository::class),
            'student' => $this->container->make(StudentAssignmentRepository::class),
        };
    }
}
