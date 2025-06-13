<?php

namespace App\Repositories\Project;

use App\DataTransferObjects\Project\ProjectDto;
use App\Enums\Upload\UploadMessage;

interface ProjectRepositoryInterface
{
    public function all(ProjectDto $dto): object;

    public function find(int $id): object;

    public function create(ProjectDto $dto): object;

    public function update(ProjectDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id, string $fileName): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id, string $fileName): void;
}
