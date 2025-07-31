<?php

namespace App\Policies\AssignmentSubmit;

use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;
use App\Models\Assignment\Assignment;

class AssignmentSubmitPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'admin')
        {
            return false;
        }
    }

    public function index(User $user, string $model, int $assignmentId): bool
    {
        $assignment = Assignment::find($assignmentId);

        return ($this->checkIfEnrolled($user, $assignment->course->id) ||
            $this->checkIfOwned($user, $assignment->course->id));
    }

    public function show(User $user, AssignmentSubmit $assignmentSubmit): bool
    {
        return ($this->checkIfBelonged($user, $assignmentSubmit) ||
            $this->checkIfOwned($user, $assignmentSubmit->assignment->course->id));
    }

    public function update(User $user, AssignmentSubmit $assignmentSubmit): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assignmentSubmit->assignment->course->id));
    }

    public function destroy(User $user, AssignmentSubmit $assignmentSubmit): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assignmentSubmit->assignment->course->id));
    }

    public function view(User $user, AssignmentSubmit $assignmentSubmit): bool
    {
        return ($this->checkIfBelonged($user, $assignmentSubmit) ||
            $this->checkIfOwned($user, $assignmentSubmit->assignment->course->id));
    }

    public function download(User $user, AssignmentSubmit $assignmentSubmit): bool
    {
        return ($this->checkIfBelonged($user, $assignmentSubmit) ||
            $this->checkIfOwned($user, $assignmentSubmit->assignment->course->id));
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

    private function checkIfBelonged(User $user, AssignmentSubmit $assignmentSubmit): bool
    {
        return $assignmentSubmit->student_id == $user->id ? true : false;
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
