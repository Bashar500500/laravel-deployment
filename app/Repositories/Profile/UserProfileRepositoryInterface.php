<?php

namespace App\Repositories\Profile;

use App\DataTransferObjects\Profile\UserProfileDto;
use App\Enums\Upload\UploadMessage;

interface UserProfileRepositoryInterface
{
    public function all(UserProfileDto $dto, array $data): object;

    public function allWithFilter(UserProfileDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(UserProfileDto $dto, array $data): object;

    public function update(UserProfileDto $dto, int $id, array $data): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
