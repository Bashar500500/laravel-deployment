<?php

namespace App\Policies\User;

use App\Models\User\User;

class UserPolicy
{
    public function index(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function show(User $user, int $userId): bool
    {
        return $this->checkIfBelonged($user, $userId);
    }

    public function update(User $user, int $userId): bool
    {
        return $this->checkIfBelonged($user, $userId);
    }

    public function destroy(User $user, int $userId): bool
    {
        return $this->checkIfBelonged($user, $userId);
    }

    private function checkIfBelonged(User $user, int $userId): bool
    {
        return $userId == $user->id ? true : false;
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
