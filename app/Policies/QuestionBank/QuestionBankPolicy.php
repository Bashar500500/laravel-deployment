<?php

namespace App\Policies\QuestionBank;

use App\Models\QuestionBank\QuestionBank;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class QuestionBankPolicy
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

    public function index(User $user, string $model, int $courseId): bool
    {
        return $this->checkIfOwned($user, $courseId);
    }

    public function show(User $user, QuestionBank $questionBank): bool
    {
        return $this->checkIfOwned($user, $questionBank->course_id);
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function destroy(User $user, QuestionBank $questionBank): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $questionBank->course_id));
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
