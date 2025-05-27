<?php

namespace App\Factories\Course;

use Illuminate\Contracts\Container\Container;
use App\Repositories\Course\CourseRepositoryInterface;
use App\Repositories\Course\AdminCourseRepository;
use App\Repositories\Course\InstructorCourseRepository;
use App\Repositories\Course\StudentCourseRepository;

class CourseRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): CourseRepositoryInterface
    {
        return match ($role) {
            'admin' => $this->container->make(AdminCourseRepository::class),
            'instructor' => $this->container->make(InstructorCourseRepository::class),
            'student' => $this->container->make(StudentCourseRepository::class),
        };
    }
}
