<?php

namespace App\Repositories\Category;

use App\DataTransferObjects\Category\CategoryDto;
use App\Enums\Upload\UploadMessage;

interface CategoryRepositoryInterface
{
    public function all(CategoryDto $dto): object;

    public function find(int $id): object;

    public function create(CategoryDto $dto): object;

    public function update(CategoryDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
