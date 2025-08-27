<?php

namespace App\Policies\User;

use App\Models\InstructorStudent\InstructorStudent;
use App\Models\User\User;
use App\Models\UserCourseGroup\UserCourseGroup;
use Illuminate\Auth\Access\Response;

class AdminAndUserPolicy
{
    public function adminIndex(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminShow(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminStore(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminUpdate(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDestroy(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function userShow(User $user, string $model, int $userId): bool
    {
        return $this->checkIfBelonged($user, $userId);
    }

    public function userStore(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function addStudentToCourse(User $user, string $model, int $courseId): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $courseId));
    }

    public function removeStudentFromCourse(User $user, string $model, string $studentCode): bool
    {
        $exists = UserCourseGroup::where('student_code', $studentCode)->first();

        if (! $exists)
        {
            return true;
        }

        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $exists->course->id));
    }

    public function instructorFileNames(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function studentFileNames(User $user): bool
    {
        return $this->checkStudentRole($user);
    }

    public function removeStudentFromInstructorList(User $user, string $model, string $studentId): bool
    {
        $exists = InstructorStudent::where('instructor_id', $user->id)->where('student_id', $studentId)->first();
        return ($this->checkInstructorRole($user) && $exists);
    }

    private function checkIfBelonged(User $user, int $userId): bool
    {
        $coursesA = UserCourseGroup::where('student_id', $user->id)->pluck('course_id');
        $coursesB = UserCourseGroup::where('student_id', $userId)->pluck('course_id');
        return $coursesA->intersect($coursesB)->isNotEmpty();
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

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
