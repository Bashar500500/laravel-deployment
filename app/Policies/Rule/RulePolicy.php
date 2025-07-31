<?php

namespace App\Policies\Rule;

use App\Models\Rule\Rule;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class RulePolicy
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

    public function store(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function update(User $user, Rule $rule): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroy(User $user, Rule $rule): bool
    {
        return $this->checkAdminRole($user);
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
