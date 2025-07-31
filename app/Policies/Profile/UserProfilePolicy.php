<?php

namespace App\Policies\Profile;

use App\Models\Profile\Profile;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class UserProfilePolicy
{
    public function index(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function show(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile);
    }

    public function update(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile);
    }

    public function destroy(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile);
    }

    public function view(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile);
    }

    public function download(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile);
    }

    public function upload(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile);
    }

    public function destroyAttachment(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile);
    }

    private function checkIfBelonged(User $user, Profile $profile): bool
    {
        return $profile->user_id == $user->id ? true : false;
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
