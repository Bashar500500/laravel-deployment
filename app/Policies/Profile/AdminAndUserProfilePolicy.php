<?php

namespace App\Policies\Profile;

use App\Models\Profile\Profile;
use App\Models\User\User;
use App\Models\UserCourseGroup\UserCourseGroup;
use Illuminate\Auth\Access\Response;

class AdminAndUserProfilePolicy
{
    public function adminIndex(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminShow(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminStore(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminUpdate(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDestroy(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminView(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDownload(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminUpload(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function adminDestroyAttachment(User $user, Profile $profile): bool
    {
        return $this->checkAdminRole($user);
    }

    public function userShow(User $user, Profile $profile): bool
    {
        return $this->checkIfBelonged($user, $profile->user->id);
    }

    private function checkIfBelonged(User $user, int $userId): bool
    {
        $coursesA = UserCourseGroup::where('student_id', $user->id)->pluck('course_id');
        $coursesB = UserCourseGroup::where('student_id', $userId)->pluck('course_id');
        return $coursesA->intersect($coursesB)->isNotEmpty();
    }

    public function checkAdminRole(User $user): bool
    {
        $role = $user->getRoleNames();
        return $role[0] == 'admin' ? true : false;
    }
}
