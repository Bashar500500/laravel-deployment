<?php

namespace App\Repositories\ProjectSubmit;

use App\DataTransferObjects\ProjectSubmit\ProjectSubmitDto;

interface ProjectSubmitRepositoryInterface
{
    public function all(ProjectSubmitDto $dto): object;

    public function find(int $id): object;

    public function update(ProjectSubmitDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id, string $fileName): string;

    public function download(int $id): string;
}
