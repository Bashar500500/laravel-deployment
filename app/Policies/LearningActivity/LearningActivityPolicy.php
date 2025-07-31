<?php

namespace App\Policies\LearningActivity;

use App\Models\LearningActivity\LearningActivity;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;
use App\Models\Section\Section;

class LearningActivityPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'admin')
        {
            return false;
        }
    }

    public function index(User $user, int $sectionId): bool
    {
        $section = Section::find($sectionId);

        return ($this->checkIfEnrolled($user, $section->course->id) ||
            $this->checkIfOwned($user, $section->course->id));
    }

    public function show(User $user, LearningActivity $learningActivity): bool
    {
        return ($this->checkIfEnrolled($user, $learningActivity->section->course->id) ||
            $this->checkIfOwned($user, $learningActivity->section->course->id));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, LearningActivity $learningActivity): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $learningActivity->section->course->id));
    }

    public function destroy(User $user, LearningActivity $learningActivity): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $learningActivity->section->course->id));
    }

    public function view(User $user, LearningActivity $learningActivity): bool
    {
        return ($this->checkIfEnrolled($user, $learningActivity->section->course->id) ||
            $this->checkIfOwned($user, $learningActivity->section->course->id));
    }

    public function download(User $user, LearningActivity $learningActivity): bool
    {
        return ($this->checkIfEnrolled($user, $learningActivity->section->course->id) ||
            $this->checkIfOwned($user, $learningActivity->section->course->id));
    }

    public function upload(User $user, LearningActivity $learningActivity): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $learningActivity->section->course->id));
    }

    public function destroyAttachment(User $user, LearningActivity $learningActivity): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $learningActivity->section->course->id));
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
}
