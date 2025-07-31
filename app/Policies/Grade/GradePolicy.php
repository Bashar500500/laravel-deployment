<?php

namespace App\Policies\Grade;

use App\Models\Grade\Grade;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class GradePolicy
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

    public function show(User $user, Grade $grade): bool
    {
        return ($this->checkIfBelonged($user, $grade) ||
            $this->checkIfOwned($user, $grade->gradeable->course->id));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Grade $grade): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $grade->gradeable->course->id));
    }

    public function destroy(User $user, Grade $grade): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $grade->gradeable->course->id));
    }

    private function checkIfOwned(User $user, int $courseId): bool
    {
        $exists = $user->ownedCourses->where('id', $courseId)->first();
        return $exists ? true : false;
    }

    private function checkIfBelonged(User $user, Grade $grade): bool
    {
        return $grade->student_id == $user->id ? true : false;
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
