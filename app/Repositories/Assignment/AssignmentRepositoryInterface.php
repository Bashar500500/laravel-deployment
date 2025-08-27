<?php

namespace App\Repositories\Assignment;

use App\DataTransferObjects\Assignment\AssignmentDto;
use App\DataTransferObjects\Assignment\AssignmentSubmitDto;
use App\Enums\Upload\UploadMessage;

interface AssignmentRepositoryInterface
{
    public function all(AssignmentDto $dto): object;

    public function find(int $id): object;

    public function create(AssignmentDto $dto): object;

    public function update(AssignmentDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id, string $fileName): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id, string $fileName): void;

    public function submit(AssignmentSubmitDto $dto, array $data): object;
}
