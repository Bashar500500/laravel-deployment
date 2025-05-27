<?php

namespace App\Repositories\Section;

use App\DataTransferObjects\Section\SectionDto;
use App\Enums\Upload\UploadMessage;

interface SectionRepositoryInterface
{
    public function all(SectionDto $dto): object;

    public function find(int $id): object;

    public function create(SectionDto $dto): object;

    public function update(SectionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id, string $fileName): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id, string $fileName): void;
}
