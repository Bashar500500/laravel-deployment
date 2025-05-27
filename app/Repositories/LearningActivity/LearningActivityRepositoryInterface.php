<?php

namespace App\Repositories\LearningActivity;

use App\DataTransferObjects\LearningActivity\LearningActivityDto;
use App\Enums\Upload\UploadMessage;

interface LearningActivityRepositoryInterface
{
    public function all(LearningActivityDto $dto): object;

    public function find(int $id): object;

    public function create(LearningActivityDto $dto, array $data): object;

    public function update(LearningActivityDto $dto, array $data, int $id): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
