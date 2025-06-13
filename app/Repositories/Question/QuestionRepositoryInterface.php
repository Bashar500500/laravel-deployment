<?php

namespace App\Repositories\Question;

use App\DataTransferObjects\Question\QuestionDto;
use App\Enums\Upload\UploadMessage;

interface QuestionRepositoryInterface
{
    public function all(QuestionDto $dto): object;

    public function allWithFilter(QuestionDto $dto): object;

    public function find(int $id): object;

    public function create(QuestionDto $dto): object;

    public function update(QuestionDto $dto, int $id): object;

    public function delete(int $id): object;

    public function view(int $id): string;

    public function download(int $id): string;

    public function upload(int $id, array $data): UploadMessage;

    public function deleteAttachment(int $id): void;
}
