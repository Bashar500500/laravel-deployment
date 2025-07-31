<?php

namespace App\Policies\Leave;

use App\Models\Leave\Leave;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class LeavePolicy
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

    public function show(User $user, Leave $leave): bool
    {
        return ($this->checkIfBelonged($user, $leave) ||
            $this->checkAdminRole($user));
    }

    public function store(User $user): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function update(User $user, Leave $leave): bool
    {
        return ($this->checkIfBelonged($user, $leave) ||
            $this->checkAdminRole($user));
    }

    public function destroy(User $user, Leave $leave): bool
    {
        return ($this->checkIfBelonged($user, $leave) ||
            $this->checkAdminRole($user));
    }

    private function checkIfBelonged(User $user, Leave $leave): bool
    {
        return $leave->instructor_id == $user->id ? true : false;
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
