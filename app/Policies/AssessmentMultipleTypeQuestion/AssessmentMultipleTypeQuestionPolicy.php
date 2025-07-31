<?php

namespace App\Policies\AssessmentMultipleTypeQuestion;

use App\Models\AssessmentMultipleTypeQuestion\AssessmentMultipleTypeQuestion;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;
use App\Models\Assessment\Assessment;

class AssessmentMultipleTypeQuestionPolicy
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

    public function index(User $user, string $model, int $assessmentId): bool
    {
        $assessment = Assessment::find($assessmentId);

        return $this->checkIfOwned($user, $assessment->course->id);
    }

    public function show(User $user, AssessmentMultipleTypeQuestion $assessmentMultipleTypeQuestion): bool
    {
        return $this->checkIfOwned($user, $assessmentMultipleTypeQuestion->assessment->course->id);
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, AssessmentMultipleTypeQuestion $assessmentMultipleTypeQuestion): bool
    {
        return $this->checkIfOwned($user, $assessmentMultipleTypeQuestion->assessment->course->id);
    }

    public function destroy(User $user, AssessmentMultipleTypeQuestion $assessmentMultipleTypeQuestion): bool
    {
        return $this->checkIfOwned($user, $assessmentMultipleTypeQuestion->assessment->course->id);
    }

    public function addAssessmentMultipleTypeQuestionToQuestionBank(User $user, AssessmentMultipleTypeQuestion $assessmentMultipleTypeQuestion): bool
    {
        return $this->checkIfOwned($user, $assessmentMultipleTypeQuestion->assessment->course->id);
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
