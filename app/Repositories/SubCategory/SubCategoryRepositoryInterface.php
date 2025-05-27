<?php

namespace App\Repositories\SubCategory;

use App\DataTransferObjects\SubCategory\SubCategoryDto;
use App\Enums\Upload\UploadMessage;

interface SubCategoryRepositoryInterface
{
    public function all(SubCategoryDto $dto): object;

    public function find(int $id): object;

    public function create(SubCategoryDto $dto): object;

    public function update(SubCategoryDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
