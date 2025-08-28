<?php

namespace App\Repositories\InteractiveContent;

use App\DataTransferObjects\InteractiveContent\InteractiveContentDto;
use App\Enums\Upload\UploadMessage;

interface InteractiveContentRepositoryInterface
{
    public function all(InteractiveContentDto $dto, array $data): object;

    public function find(int $id): object;

    public function create(InteractiveContentDto $dto, array $data): object;

    public function update(InteractiveContentDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
