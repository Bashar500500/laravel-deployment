<?php

namespace App\Policies\Progress;

use App\Models\Progress\Progress;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class ProgressPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'admin')
        {
            return false;
        }
    }

    public function index(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function show(User $user, Progress $progress): bool
    {
        return ($this->checkIfBelonged($user, $progress) ||
            $this->checkIfOwned($user, $progress->course_id));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Progress $progress): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $progress->course_id));
    }

    public function destroy(User $user, Progress $progress): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $progress->course_id));
    }

    private function checkIfOwned(User $user, int $courseId): bool
    {
        $exists = $user->ownedCourses->where('id', $courseId)->first();
        return $exists ? true : false;
    }

    private function checkIfBelonged(User $user, Progress $progress): bool
    {
        return $progress->student_id == $user->id ? true : false;
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
