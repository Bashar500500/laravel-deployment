<?php

namespace App\Repositories\Group;

use App\DataTransferObjects\Group\GroupDto;
use App\Enums\Upload\UploadMessage;

interface GroupRepositoryInterface
{
    public function all(GroupDto $dto, array $data): object;

    public function allWithFilter(GroupDto $dto): object;

    public function find(int $id): object;

    public function create(GroupDto $dto): object;

    public function update(GroupDto $dto, int $id): object;

    public function delete(int $id): object;

    public function join(int $id, array $data): void;

    public function leave(int $id, array $data): void;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
