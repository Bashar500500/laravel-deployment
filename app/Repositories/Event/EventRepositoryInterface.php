<?php

namespace App\Repositories\Event;

use App\DataTransferObjects\Event\EventDto;
use App\Enums\Upload\UploadMessage;

interface EventRepositoryInterface
{
    public function all(EventDto $dto): object;

    public function allWithFilter(EventDto $dto): object;

    public function find(int $id): object;

    public function create(EventDto $dto): object;

    public function update(EventDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id, string $fileName): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id, string $fileName): void;
}
