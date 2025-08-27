<?php

namespace App\Policies\Prerequisite;

use App\Models\Prerequisite\Prerequisite;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class PrerequisitePolicy
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

    public function show(User $user, Prerequisite $prerequisite): bool
    {
        return ($this->checkInstructorRole($user) &&
            $prerequisite->instructor_id == $user->id);
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Prerequisite $prerequisite): bool
    {
        return ($this->checkInstructorRole($user) &&
            $prerequisite->instructor_id == $user->id);
    }

    public function destroy(User $user, Prerequisite $prerequisite): bool
    {
        return ($this->checkInstructorRole($user) &&
            $prerequisite->instructor_id == $user->id);
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
