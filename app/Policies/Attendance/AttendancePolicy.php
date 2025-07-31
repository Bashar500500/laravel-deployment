<?php

namespace App\Policies\Attendance;

use App\Models\Attendance\Attendance;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;
use App\Models\Section\Section;

class AttendancePolicy
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

    public function index(User $user, string $model, int $sectionId): bool
    {
        $section = Section::find($sectionId);

        return $this->checkIfOwned($user, $section->course->id);
    }

    public function show(User $user, Attendance $attendance): bool
    {
        return $this->checkIfOwned($user, $attendance->section->course->id);
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $this->checkIfOwned($user, $attendance->section->course->id);
    }

    public function destroy(User $user, Attendance $attendance): bool
    {
        return $this->checkIfOwned($user, $attendance->section->course->id);
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
}
