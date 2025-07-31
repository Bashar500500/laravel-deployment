<?php

namespace App\Policies\Policy;

use App\Models\Policy\Policy;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class PolicyPolicy
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
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function show(User $user, Policy $policy): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function store(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function update(User $user, Policy $policy): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroy(User $user, Policy $policy): bool
    {
        return $this->checkAdminRole($user);
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
