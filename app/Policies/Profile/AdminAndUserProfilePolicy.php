<?php

namespace App\Policies\Profile;

use App\Models\Profile\Profile;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class AdminAndUserProfilePolicy
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

    public function adminIndex(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminShow(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminStore(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminUpdate(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDestroy(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminView(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDownload(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminUpload(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDestroyAttachment(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function userIndex(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
