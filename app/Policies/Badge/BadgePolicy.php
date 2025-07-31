<?php

namespace App\Policies\Badge;

use App\Models\Badge\Badge;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class BadgePolicy
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

    public function show(User $user, Badge $badge): bool
    {
        return ($this->checkIfEnrolled($user, $badge) ||
            $this->checkIfOwned($user, $badge));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Badge $badge): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $badge));
    }

    public function destroy(User $user, Badge $badge): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $badge));
    }

    private function checkIfEnrolled(User $user, Badge $badge): bool
    {
        $exists = $user->enrolledCourses->where('instructor_id', $badge->instructor_id)->first();
        return $exists ? true : false;
    }

    private function checkIfOwned(User $user, Badge $badge): bool
    {
        return $badge->instructor_id == $user->id ? true : false;
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
