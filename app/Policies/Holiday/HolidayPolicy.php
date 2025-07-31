<?php

namespace App\Policies\Holiday;

use App\Models\Holiday\Holiday;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class HolidayPolicy
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

    public function show(User $user, Holiday $holiday): bool
    {
        return ($this->checkIfBelonged($user, $holiday) ||
            $this->checkAdminRole($user));
    }

    public function store(User $user): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function update(User $user, Holiday $holiday): bool
    {
        return ($this->checkIfBelonged($user, $holiday) ||
            $this->checkAdminRole($user));
    }

    public function destroy(User $user, Holiday $holiday): bool
    {
        return ($this->checkIfBelonged($user, $holiday) ||
            $this->checkAdminRole($user));
    }

    private function checkIfBelonged(User $user, Holiday $holiday): bool
    {
        return $holiday->instructor_id == $user->id ? true : false;
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
