<?php

namespace App\Policies\AssessmentSubmit;

use App\Models\AssessmentSubmit\AssessmentSubmit;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;
use App\Models\Assessment\Assessment;

class AssessmentSubmitPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'admin')
        {
            return false;
        }
    }

    public function index(User $user, string $model, int $assessmentId): bool
    {
        $assessment = Assessment::find($assessmentId);

        return ($this->checkIfEnrolled($user, $assessment->course->id) ||
            $this->checkIfOwned($user, $assessment->course->id));
    }

    public function show(User $user, AssessmentSubmit $assessmentSubmit): bool
    {
        return ($this->checkIfBelonged($user, $assessmentSubmit) ||
            $this->checkIfOwned($user, $assessmentSubmit->assessment->course->id));
    }

    public function update(User $user, AssessmentSubmit $assessmentSubmit): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessmentSubmit->assessment->course->id));
    }

    public function destroy(User $user, AssessmentSubmit $assessmentSubmit): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $assessmentSubmit->assessment->course->id));
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

    private function checkIfBelonged(User $user, AssessmentSubmit $assessmentSubmit): bool
    {
        return $assessmentSubmit->student_id == $user->id ? true : false;
    }

    public function checkInstructorRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'instructor' ? true : false;
    }
}
