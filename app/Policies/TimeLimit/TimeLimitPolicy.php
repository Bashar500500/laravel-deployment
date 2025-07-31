<?php

namespace App\Policies\TimeLimit;

use App\Models\TimeLimit\TimeLimit;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class TimeLimitPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'admin')
        {
            return false;
        }
        else if ($role[0] == 'student')
        {
            return false;
        }
    }

    public function index(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function show(User $user, TimeLimit $timeLimit): bool
    {
        return $this->checkIfBelonged($user, $timeLimit);
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, TimeLimit $timeLimit): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkIfBelonged($user, $timeLimit));
    }

    public function destroy(User $user, TimeLimit $timeLimit): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkIfBelonged($user, $timeLimit));
    }

    private function checkIfBelonged(User $user, TimeLimit $timeLimit): bool
    {
        return $timeLimit->instructor_id == $user->id ? true : false;
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
