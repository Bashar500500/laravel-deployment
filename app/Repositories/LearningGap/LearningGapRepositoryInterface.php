<?php

namespace App\Repositories\LearningGap;

use App\DataTransferObjects\LearningGap\LearningGapDto;

interface LearningGapRepositoryInterface
{
    public function all(LearningGapDto $dto): object;

    public function find(int $id): object;

    public function create(LearningGapDto $dto): object;

    public function update(LearningGapDto $dto, int $id): object;

    public function delete(int $id): object;
}
