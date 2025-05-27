<?php

namespace App\Repositories\Profile;

use App\DataTransferObjects\Profile\AdminProfileDto;
use App\Enums\Upload\UploadMessage;

interface AdminProfileRepositoryInterface
{
    public function all(AdminProfileDto $dto): object;

    public function find(int $id): object;

    public function create(AdminProfileDto $dto, array $data): object;

    public function update(AdminProfileDto $dto, int $id, array $data): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
