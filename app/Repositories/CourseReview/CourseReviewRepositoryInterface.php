<?php

namespace App\Repositories\CourseReview;

use App\DataTransferObjects\CourseReview\CourseReviewDto;

interface CourseReviewRepositoryInterface
{
    public function all(CourseReviewDto $dto): object;

    public function find(int $id): object;

    public function create(CourseReviewDto $dto, array $data): object;

    public function update(CourseReviewDto $dto, int $id): object;

    public function delete(int $id): object;
}
