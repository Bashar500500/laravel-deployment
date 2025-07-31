<?php

namespace App\Policies\Course;

use App\Models\Course\Course;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    public function index(User $user): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user) || $this->checkStudentRole($user));
    }

    public function show(User $user, Course $course): bool
    {
        return ($this->checkIfEnrolled($user, $course->id) ||
            $this->checkIfOwned($user, $course->id));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Course $course): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $course->id)) ||
            ($this->checkAdminRole($user));
    }

    public function destroy(User $user, Course $course): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $course->id));
    }

    public function view(User $user, Course $course): bool
    {
        return ($this->checkIfEnrolled($user, $course->id) ||
            $this->checkIfOwned($user, $course->id));
    }

    public function download(User $user, Course $course): bool
    {
        return ($this->checkIfEnrolled($user, $course->id) ||
            $this->checkIfOwned($user, $course->id));
    }

    public function upload(User $user, Course $course): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $course->id));
    }

    public function destroyAttachment(User $user, Course $course): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $course->id));
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

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }

    public function checkStudentRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'student' ? true : false;
    }
}
