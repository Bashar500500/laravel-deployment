<?php

namespace App\Policies\Rubric;

use App\Models\Rubric\Rubric;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class RubricPolicy
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

    public function show(User $user, Rubric $rubric): bool
    {
        return ($this->checkInstructorRole($user) &&
            $rubric->instructor_id == $user->id);
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Rubric $rubric): bool
    {
        return ($this->checkInstructorRole($user) &&
            $rubric->instructor_id == $user->id);
    }

    public function destroy(User $user, Rubric $rubric): bool
    {
        return ($this->checkInstructorRole($user) &&
            $rubric->instructor_id == $user->id);
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
