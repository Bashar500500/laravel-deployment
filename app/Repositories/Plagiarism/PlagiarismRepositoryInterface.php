<?php

namespace App\Repositories\Plagiarism;

use App\DataTransferObjects\Plagiarism\PlagiarismDto;

interface PlagiarismRepositoryInterface
{
    public function all(PlagiarismDto $dto): object;

    public function find(int $id): object;

    public function update(PlagiarismDto $dto, int $id): object;

    public function delete(int $id): object;
}
