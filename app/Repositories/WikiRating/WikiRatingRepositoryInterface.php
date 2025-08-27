<?php

namespace App\Repositories\WikiRating;

use App\DataTransferObjects\WikiRating\WikiRatingDto;

interface WikiRatingRepositoryInterface
{
    public function all(WikiRatingDto $dto): object;

    public function find(int $id): object;

    public function create(WikiRatingDto $dto, array $data): object;

    public function update(WikiRatingDto $dto, int $id): object;

    public function delete(int $id): object;
}
