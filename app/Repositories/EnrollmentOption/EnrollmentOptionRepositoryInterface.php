<?php

namespace App\Repositories\EnrollmentOption;

use App\DataTransferObjects\EnrollmentOption\EnrollmentOptionDto;

interface EnrollmentOptionRepositoryInterface
{
    public function all(EnrollmentOptionDto $dto): object;

    public function find(int $id): object;

    public function create(EnrollmentOptionDto $dto): object;

    public function update(EnrollmentOptionDto $dto, int $id): object;

    public function delete(int $id): object;
}
