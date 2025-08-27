<?php

namespace App\Policies\SupportTicket;

use App\Models\SupportTicket\SupportTicket;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class SupportTicketPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'student')
        {
            return false;
        }
    }

    public function index(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function show(User $user, SupportTicket $supportTicket): bool
    {
        return $this->checkAdminRole($user);
    }

    public function store(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function update(User $user, SupportTicket $supportTicket): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroy(User $user, SupportTicket $supportTicket): bool
    {
        return $this->checkAdminRole($user);
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
