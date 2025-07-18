<?php

namespace App\Services\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\User\UserRequest;
use App\Models\User\User;
use App\DataTransferObjects\User\UserDto;
use App\Http\Requests\User\AddUserToCourseRequest;
use App\Http\Requests\User\RemoveUserFromCourseRequest;
use App\DataTransferObjects\User\UserCourseDto;
use App\Enums\User\UserMessage;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function index(UserRequest $request): object
    {
        $dto = UserDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(User $user): object
    {
        return $this->repository->find($user->id);
    }

    public function user(): object
    {
        return $this->repository->find(Auth::user()->id);
    }

    public function store(UserRequest $request): object
    {
        $dto = UserDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(UserRequest $request): object
    {
        $dto = UserDto::fromUpdateRequest($request);
        return $this->repository->update($dto, Auth::user()->id);
    }

    public function destroy(): object
    {
        return $this->repository->delete(Auth::user()->id);
    }

    public function addStudentToCourse(AddUserToCourseRequest $request): UserMessage
    {
        $dto = UserCourseDto::fromAddStudentToCourseRequest($request);
        $message = $this->repository->addStudentToCourse($dto);
        return $message;
    }

    public function removeStudentFromCourse(RemoveUserFromCourseRequest $request): void
    {
        $dto = UserCourseDto::fromRemoveStudentFromCourseRequest($request);
        $this->repository->removeStudentFromCourse($dto);
    }
}
