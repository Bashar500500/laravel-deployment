<?php

namespace App\Factories\User;

use Illuminate\Contracts\Container\Container;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\InstructorRepository;
use App\Repositories\User\StudentRepository;

class UserRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): UserRepositoryInterface
    {
        return match ($role) {
            'admin' => $this->container->make(InstructorRepository::class),
            'instructor' => $this->container->make(InstructorRepository::class),
            'student' => $this->container->make(StudentRepository::class),
        };
    }
}
