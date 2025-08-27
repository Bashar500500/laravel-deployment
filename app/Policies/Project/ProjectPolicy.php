<?php

namespace App\Policies\Project;

use App\Models\Project\Project;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
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

    public function show(User $user, Project $project): bool
    {
        return ($this->checkIfEnrolled($user, $project->course->id) ||
            $this->checkIfOwned($user, $project->course->id));
    }

    public function store(User $user): bool
    {
        return $this->checkInstructorRole($user);
    }

    public function update(User $user, Project $project): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $project->course->id));
    }

    public function destroy(User $user, Project $project): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $project->course->id));
    }

    public function view(User $user, Project $project): bool
    {
        return (($this->checkIfEnrolled($user, $project->course->id) &&
            $this->checkIfExists($user, $project)) ||
            $this->checkIfOwned($user, $project->course->id));
    }

    public function download(User $user, Project $project): bool
    {
        return (($this->checkIfEnrolled($user, $project->course->id) &&
            $this->checkIfExists($user, $project)) ||
            $this->checkIfOwned($user, $project->course->id));
    }

    public function upload(User $user, Project $project): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $project->course->id));
    }

    public function destroyAttachment(User $user, Project $project): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $project->course->id));
    }

    public function submit(User $user, string $model, int $projectId): bool
    {
        $project = Project::find($projectId);

        return ($this->checkIfEnrolled($user, $project->course->id) &&
            $this->checkIfExists($user, $project));
    }

    private function checkIfEnrolled(User $user, int $courseId): bool
    {
        $exists = $user->enrolledCourses->where('id', $courseId)->first();
        return $exists ? true : false;
    }

    private function checkIfExists(User $user, Project $project): bool
    {
        $studentIds = $project->group->students->pluck('student_id')->toArray();
        return ($user->id == $project->leader_id) || in_array($user->id, $studentIds);
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
