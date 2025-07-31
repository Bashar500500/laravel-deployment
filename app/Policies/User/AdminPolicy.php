<?php

namespace App\Policies\User;

use App\Models\User\User;

class AdminPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'student')
        {
            return false;
        }
        else if ($role[0] == 'instructor')
        {
            return false;
        }
    }

    public function index(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function show(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function store(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function update(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroy(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
