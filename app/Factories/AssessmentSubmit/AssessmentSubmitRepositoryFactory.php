<?php

namespace App\Factories\AssessmentSubmit;

use Illuminate\Contracts\Container\Container;
use App\Repositories\AssessmentSubmit\AssessmentSubmitRepositoryInterface;
use App\Repositories\AssessmentSubmit\InstructorAssessmentSubmitRepository;
use App\Repositories\AssessmentSubmit\StudentAssessmentSubmitRepository;

class AssessmentSubmitRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): AssessmentSubmitRepositoryInterface
    {
        return match ($role) {
            'instructor' => $this->container->make(InstructorAssessmentSubmitRepository::class),
            'student' => $this->container->make(StudentAssessmentSubmitRepository::class),
        };
    }
}
