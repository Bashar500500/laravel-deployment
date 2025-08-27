<?php

namespace App\Policies\Plagiarism;

use App\Models\Plagiarism\Plagiarism;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class PlagiarismPolicy
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

    public function show(User $user, Plagiarism $plagiarism): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $plagiarism->assignmentSubmit->assignment->course->id));
    }

    public function update(User $user, Plagiarism $plagiarism): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $plagiarism->assignmentSubmit->assignment->course->id));
    }

    public function destroy(User $user, Plagiarism $plagiarism): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $plagiarism->assignmentSubmit->assignment->course->id));
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
