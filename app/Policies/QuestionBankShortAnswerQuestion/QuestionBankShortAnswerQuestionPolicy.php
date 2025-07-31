<?php

namespace App\Policies\QuestionBankShortAnswerQuestion;

use App\Models\QuestionBankShortAnswerQuestion\QuestionBankShortAnswerQuestion;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;
use App\Models\QuestionBank\QuestionBank;

class QuestionBankShortAnswerQuestionPolicy
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

    public function index(User $user, string $model, int $questionBankId): bool
    {
        $questionBank = QuestionBank::find($questionBankId);

        return $this->checkIfOwned($user, $questionBank->course->id);
    }

    public function show(User $user, QuestionBankShortAnswerQuestion $questionBankShortAnswerQuestion): bool
    {
        return $this->checkIfOwned($user, $questionBankShortAnswerQuestion->questionBank->course->id);
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, QuestionBankShortAnswerQuestion $questionBankShortAnswerQuestion): bool
    {
        return $this->checkIfOwned($user, $questionBankShortAnswerQuestion->questionBank->course->id);
    }

    public function destroy(User $user, QuestionBankShortAnswerQuestion $questionBankShortAnswerQuestion): bool
    {
        return $this->checkIfOwned($user, $questionBankShortAnswerQuestion->questionBank->course->id);
    }

    public function addQuestionBankShortAnswerQuestionToAssessment(User $user, QuestionBankShortAnswerQuestion $questionBankShortAnswerQuestion): bool
    {
        return $this->checkIfOwned($user, $questionBankShortAnswerQuestion->questionBank->course->id);
    }

    public function removeQuestionBankShortAnswerQuestionFromAssessment(User $user, QuestionBankShortAnswerQuestion $questionBankShortAnswerQuestion): bool
    {
        return $this->checkIfOwned($user, $questionBankShortAnswerQuestion->questionBank->course->id);
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
