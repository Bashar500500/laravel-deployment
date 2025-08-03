<?php

namespace App\Factories\Profile;

use Illuminate\Contracts\Container\Container;
use App\Repositories\Profile\UserProfileRepositoryInterface;
use App\Repositories\Profile\InstructorProfileRepository;
use App\Repositories\Profile\StudentProfileRepository;

class UserProfileRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): UserProfileRepositoryInterface
    {
        return match ($role) {
            'admin' => $this->container->make(InstructorProfileRepository::class),
            'instructor' => $this->container->make(InstructorProfileRepository::class),
            'student' => $this->container->make(StudentProfileRepository::class),
        };
    }
}
