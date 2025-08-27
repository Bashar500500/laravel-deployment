<?php

namespace App\Policies\ProjectSubmit;

use App\Models\ProjectSubmit\ProjectSubmit;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;
use App\Models\Project\Project;

class ProjectSubmitPolicy
{
    public function before(User $user, $ability)
    {
        $role = $user->getRoleNames();
        if ($role[0] == 'admin')
        {
            return false;
        }
    }

    public function index(User $user, string $model, int $projectId): bool
    {
        $project = Project::find($projectId);

        return (($this->checkIfEnrolled($user, $project->course->id) &&
            $this->checkIfExists($user, $project)) ||
            $this->checkIfOwned($user, $project->course->id));
    }

    public function show(User $user, ProjectSubmit $projectSubmit): bool
    {
        return (($this->checkIfEnrolled($user, $projectSubmit->project->course->id) &&
            $this->checkIfExists($user, $projectSubmit->project)) ||
            $this->checkIfOwned($user, $projectSubmit->project->course->id));
    }

    public function update(User $user, ProjectSubmit $projectSubmit): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $projectSubmit->project->course->id));
    }

    public function destroy(User $user, ProjectSubmit $projectSubmit): bool
    {
        return ($this->checkInstructorRole($user) &&
            $this->checkIfOwned($user, $projectSubmit->project->course->id));
    }

    public function view(User $user, ProjectSubmit $projectSubmit): bool
    {
        return (($this->checkIfEnrolled($user, $projectSubmit->project->course->id) &&
            $this->checkIfExists($user, $projectSubmit->project)) ||
            $this->checkIfOwned($user, $projectSubmit->project->course->id));
    }

    public function download(User $user, ProjectSubmit $projectSubmit): bool
    {
        return (($this->checkIfEnrolled($user, $projectSubmit->project->course->id) &&
            $this->checkIfExists($user, $projectSubmit->project)) ||
            $this->checkIfOwned($user, $projectSubmit->project->course->id));
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
}
