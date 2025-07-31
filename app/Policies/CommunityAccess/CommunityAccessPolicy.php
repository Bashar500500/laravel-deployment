<?php

namespace App\Policies\CommunityAccess;

use App\Models\CommunityAccess\CommunityAccess;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class CommunityAccessPolicy
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

    public function show(User $user, CommunityAccess $communityAccess): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function store(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function update(User $user, CommunityAccess $communityAccess): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroy(User $user, CommunityAccess $communityAccess): bool
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
