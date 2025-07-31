<?php

namespace App\Policies\Assessment;

use App\Models\Assessment\Assessment;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class AssessmentPolicy
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

    public function show(User $user, Assessment $assessment): bool
    {
        return ($this->checkIfEnrolled($user, $assessment->course->id) ||
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Assessment $assessment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function destroy(User $user, Assessment $assessment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function submit(User $user, string $model, int $assessmentId): bool
    {
        $assessment = Assessment::find($assessmentId);

        return ($this->checkStudentRole($user) &&
            $this->checkIfEnrolled($user, $assessment->course->id));
    }

    public function startTimer(User $user, Assessment $assessment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function pauseTimer(User $user, Assessment $assessment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function resumeTimer(User $user, Assessment $assessment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function submitTimer(User $user, Assessment $assessment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function timerStatus(User $user, Assessment $assessment): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessment->course->id));
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
