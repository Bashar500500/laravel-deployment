<?php

namespace App\Factories\SupportTicket;

use Illuminate\Contracts\Container\Container;
use App\Repositories\SupportTicket\SupportTicketRepositoryInterface;
use App\Repositories\SupportTicket\AdminSupportTicketRepository;
use App\Repositories\SupportTicket\InstructorSupportTicketRepository;
use App\Repositories\SupportTicket\StudentSupportTicketRepository;

class SupportTicketRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(string $role): SupportTicketRepositoryInterface
    {
        return match ($role) {
            'admin' => $this->container->make(AdminSupportTicketRepository::class),
            'instructor' => $this->container->make(InstructorSupportTicketRepository::class),
            'student' => $this->container->make(StudentSupportTicketRepository::class),
        };
    }
}
