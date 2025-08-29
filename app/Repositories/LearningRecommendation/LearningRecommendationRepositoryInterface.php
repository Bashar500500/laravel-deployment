<?php

namespace App\Repositories\LearningRecommendation;

use App\DataTransferObjects\LearningRecommendation\LearningRecommendationDto;

interface LearningRecommendationRepositoryInterface
{
    public function all(LearningRecommendationDto $dto): object;

    public function find(int $id): object;

    public function create(LearningRecommendationDto $dto): object;

    public function update(LearningRecommendationDto $dto, int $id): object;

    public function delete(int $id): object;
}
