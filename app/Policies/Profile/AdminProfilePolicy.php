<?php

namespace App\Policies\Profile;

use App\Models\Profile\Profile;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class AdminProfilePolicy
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

    public function show(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function store(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function update(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroy(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function view(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function download(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function upload(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroyAttachment(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
