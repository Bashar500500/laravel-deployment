<?php

namespace App\Repositories\ReusableContent;

use App\DataTransferObjects\ReusableContent\ReusableContentDto;
use App\Enums\Upload\UploadMessage;

interface ReusableContentRepositoryInterface
{
    public function all(ReusableContentDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(ReusableContentDto $dto, array $data): object;

    public function update(ReusableContentDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
