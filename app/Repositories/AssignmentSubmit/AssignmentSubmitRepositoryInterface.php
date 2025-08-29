<?php

namespace App\Repositories\AssignmentSubmit;

use App\DataTransferObjects\AssignmentSubmit\AssignmentSubmitDto;

interface AssignmentSubmitRepositoryInterface
{
    public function all(AssignmentSubmitDto $dto, array $data): object;

    public function find(int $id): object;

    public function update(AssignmentSubmitDto $dto, int $id, array $data): object;

    public function delete(int $id): object;

    public function view(int $id, string $fileName): string;

    public function download(int $id): string;
}
