<?php

namespace App\Repositories\User;

use App\DataTransferObjects\User\UserDto;
use App\DataTransferObjects\Auth\PasswordResetCodeDto;
use App\DataTransferObjects\User\UserCourseDto;
use App\Enums\User\UserMessage;

interface UserRepositoryInterface
{
    public function all(UserDto $dto, array $data): object;

    public function allWithFilter(UserDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(UserDto $dto, array $data): object;

    public function update(UserDto $dto, int $id): object;

    public function delete(int $id): object;

    public function resetPassword(PasswordResetCodeDto $dto): void;

    public function addStudentToCourse(UserCourseDto $dto): UserMessage;

    public function removeStudentFromCourse(UserCourseDto $dto): void;

    public function removeStudentFromInstructorList(UserCourseDto $dto, array $data): void;

}
