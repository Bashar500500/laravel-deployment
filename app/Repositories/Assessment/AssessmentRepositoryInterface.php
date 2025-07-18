<?php

namespace App\Repositories\Assessment;

use App\DataTransferObjects\Assessment\AssessmentDto;
use App\DataTransferObjects\Assessment\AssessmentSubmitDto;

interface AssessmentRepositoryInterface
{
    public function all(AssessmentDto $dto): object;

    public function find(int $id): object;

    public function create(AssessmentDto $dto): object;

    public function update(AssessmentDto $dto, int $id): object;

    public function delete(int $id): object;

    public function submit(AssessmentSubmitDto $dto, array $data): object;

    public function startTimer(int $id): void;

    public function pauseTimer(int $id): void;

    public function resumeTimer(int $id): void;

    public function submitTimer(int $id): void;

    public function timerStatus(int $id): void;
}
