<?php

namespace App\Services\User;

use App\Factories\User\UserRepositoryFactory;
use App\Http\Requests\User\UserRequest;
use App\Models\User\User;
use App\DataTransferObjects\User\UserDto;
use App\Http\Requests\User\AddUserToCourseRequest;
use App\Http\Requests\User\RemoveUserFromCourseRequest;
use App\DataTransferObjects\User\UserCourseDto;
use App\Enums\User\UserMessage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\RemoveStudentFromInstructorListRequest;

class UserService
{
    public function __construct(
        protected UserRepositoryFactory $factory,
    ) {}

    public function index(UserRequest $request): object
    {
        $dto = UserDto::fromIndexRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexAndStoreData();
        $repository = $this->factory->make($role[0]);
        return match ($dto->courseId) {
            null => $repository->all($dto, $data),
            default => $repository->allWithFilter($dto, $data),
        };
    }

    public function show(User $user): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find($user->id);
    }

    public function user(): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->find(Auth::user()->id);
    }

    public function store(UserRequest $request): object
    {
        $dto = UserDto::fromStoreRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexAndStoreData();
        $repository = $this->factory->make($role[0]);
        return $repository->create($dto, $data);
    }

    public function update(UserRequest $request): object
    {
        $dto = UserDto::fromUpdateRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->update($dto, Auth::user()->id);
    }

    public function destroy(): object
    {
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        return $repository->delete(Auth::user()->id);
    }

    public function addStudentToCourse(AddUserToCourseRequest $request): UserMessage
    {
        $dto = UserCourseDto::fromAddStudentToCourseRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $message = $repository->addStudentToCourse($dto);
        return $message;
    }

    public function removeStudentFromCourse(RemoveUserFromCourseRequest $request): void
    {
        $dto = UserCourseDto::fromRemoveStudentFromCourseRequest($request);
        $role = Auth::user()->getRoleNames();
        $repository = $this->factory->make($role[0]);
        $repository->removeStudentFromCourse($dto);
    }

    public function removeStudentFromInstructorList(RemoveStudentFromInstructorListRequest $request): void
    {
        $dto = UserCourseDto::fromRemoveStudentFromInstructorListRequest($request);
        $role = Auth::user()->getRoleNames();
        $data = $this->prepareIndexAndStoreData();
        $repository = $this->factory->make($role[0]);
        $repository->removeStudentFromInstructorList($dto, $data);
    }

    private function prepareIndexAndStoreData(): array
    {
        return [
            'user' => Auth::user(),
        ];
    }
}
