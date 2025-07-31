<?php

namespace App\Policies\SubCategory;

use App\Models\SubCategory\SubCategory;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class SubCategoryPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'student')
        {
            return false;
        }
    }

    public function index(User $user): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function show(User $user, SubCategory $subCategory): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function store(User $user): bool
    {
        return $this->checkAdminRole($user);
    }

    public function update(User $user, SubCategory $subCategory): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroy(User $user, SubCategory $subCategory): bool
    {
        return $this->checkAdminRole($user);
    }

    public function view(User $user, SubCategory $subCategory): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function download(User $user, SubCategory $subCategory): bool
    {
        return ($this->checkInstructorRole($user) ||
            $this->checkAdminRole($user));
    }

    public function upload(User $user, SubCategory $subCategory): bool
    {
        return $this->checkAdminRole($user);
    }

    public function destroyAttachment(User $user, SubCategory $subCategory): bool
    {
        return $this->checkAdminRole($user);
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
}
