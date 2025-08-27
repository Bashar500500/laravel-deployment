<?php

namespace App\Repositories\Wiki;

use App\DataTransferObjects\Wiki\WikiDto;
use App\Enums\Upload\UploadMessage;

interface WikiRepositoryInterface
{
    public function all(WikiDto $dto): object;

    public function find(int $id): object;

    public function create(WikiDto $dto, array $data): object;

    public function update(WikiDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id, string $fileName): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id, string $fileName): void;
}
