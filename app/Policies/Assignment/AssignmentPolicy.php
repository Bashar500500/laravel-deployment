<?php

namespace App\Policies\Assignment;

use App\Models\Assignment\Assignment;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class AssignmentPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'admin')
        {
            return false;
        }
    }

    public function index(User $user, string $model, int $courseId): bool
    {
        return ($this->checkIfEnrolled($user, $courseId) ||
            $this->checkIfOwned($user, $courseId));
    }

    public function show(User $user, Assignment $assignment): bool
    {
        return ($this->checkIfEnrolled($user, $assignment->course->id) ||
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function destroy(User $user, Assignment $assignment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return ($this->checkIfEnrolled($user, $assignment->course->id) ||
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function download(User $user, Assignment $assignment): bool
    {
        return ($this->checkIfEnrolled($user, $assignment->course->id) ||
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function upload(User $user, Assignment $assignment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function destroyAttachment(User $user, Assignment $assignment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function submit(User $user, string $model, int $assignmentId): bool
    {
        $assignment = Assignment::find($assignmentId);

        return ($this->checkStudentRole($user) &&
            $this->checkIfEnrolled($user, $assignment->course->id));
    }

    private function checkIfEnrolled(User $user, int $courseId): bool
    {
        $exists = $user->enrolledCourses->where('id', $courseId)->first();
        return $exists ? true : false;
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

    public function checkStudentRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'student' ? true : false;
    }
}
