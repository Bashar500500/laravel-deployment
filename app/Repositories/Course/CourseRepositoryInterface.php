<?php

namespace App\Repositories\Course;

use App\DataTransferObjects\Course\CourseDto;
use App\Enums\Upload\UploadMessage;

interface CourseRepositoryInterface
{
    public function all(CourseDto $dto, array $data): object;

    public function allWithFilter(CourseDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(CourseDto $dto, array $data): object;

    public function update(CourseDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
