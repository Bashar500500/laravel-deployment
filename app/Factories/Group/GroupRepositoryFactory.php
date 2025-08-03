<?php

namespace App\Factories\Group;

use Illuminate\Contracts\Container\Container;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Group\InstructorGroupRepository;
use App\Repositories\Group\StudentGroupRepository;

class GroupRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): GroupRepositoryInterface
    {
        return match ($role) {
            'instructor' => $this->container->make(InstructorGroupRepository::class),
            'student' => $this->container->make(StudentGroupRepository::class),
        };
    }
}
