<?php

namespace App\Policies\Challenge;

use App\Models\Challenge\Challenge;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class ChallengePolicy
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

    public function show(User $user, Challenge $challenge): bool
    {
        return ($this->checkIfEnrolled($user, $challenge) ||
            $this->checkIfOwned($user, $challenge));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Challenge $challenge): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $challenge));
    }

    public function destroy(User $user, Challenge $challenge): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $challenge));
    }

    public function join(User $user, Challenge $challenge): bool
    {
        return ($this->checkStudentRole($user) &&
            $this->checkIfEnrolled($user, $challenge));
    }

    public function leave(User $user, Challenge $challenge): bool
    {
        return ($this->checkStudentRole($user) &&
            $this->checkIfEnrolled($user, $challenge));
    }

    private function checkIfEnrolled(User $user, Challenge $challenge): bool
    {
        $exists = $user->enrolledCourses->where('instructor_id', $challenge->instructor_id)->first();
        return $exists ? true : false;
    }

    private function checkIfOwned(User $user, Challenge $challenge): bool
    {
        return $challenge->instructor_id == $user->id ? true : false;
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }

    public function checkStudentRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'student' ? true : false;
    }
}
