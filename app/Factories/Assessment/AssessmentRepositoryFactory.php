<?php

namespace App\Factories\Assessment;

use Illuminate\Contracts\Container\Container;
use App\Repositories\Assessment\AssessmentRepositoryInterface;
use App\Repositories\Assessment\InstructorAssessmentRepository;
use App\Repositories\Assessment\StudentAssessmentRepository;

class AssessmentRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): AssessmentRepositoryInterface
    {
        return match ($role) {
            'instructor' => $this->container->make(InstructorAssessmentRepository::class),
            'student' => $this->container->make(StudentAssessmentRepository::class),
        };
    }
}
