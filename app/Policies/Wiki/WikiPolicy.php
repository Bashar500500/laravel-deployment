<?php

namespace App\Policies\Wiki;

use App\Models\Wiki\Wiki;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class WikiPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'student')
        {
            return false;
        }
    }

    public function store(User $user): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function update(User $user, Wiki $wiki): bool
    {
        return $wiki->user_id == $user->id;
    }

    public function destroy(User $user, Wiki $wiki): bool
    {
        return $wiki->user_id == $user->id;
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
