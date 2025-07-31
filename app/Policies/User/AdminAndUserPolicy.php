<?php

namespace App\Policies\User;

use App\Models\User\User;
use App\Models\UserCourseGroup\UserCourseGroup;

class AdminAndUserPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'student')
        {
            return false;
        }
    }

    public function adminIndex(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminShow(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminStore(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminUpdate(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDestroy(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function userIndex(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function addStudentToCourse(User $user, string $model, int $courseId): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $courseId));
    }

    public function removeStudentFromCourse(User $user, string $model, string $studentCode): bool
    {
        $exists = UserCourseGroup::where('student_code', $studentCode)->first();

        if (! $exists)
        {
            return true;
        }

        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $exists->course->id));
    }

    private function checkIfOwned(User $user, int $courseId): bool
    {
        $exists = $user->ownedCourses->where('id', $courseId)->first();
        return $exists ? true : false;
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
