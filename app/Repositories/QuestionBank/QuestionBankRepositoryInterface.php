<?php

namespace App\Repositories\QuestionBank;

use App\DataTransferObjects\QuestionBank\QuestionBankDto;

interface QuestionBankRepositoryInterface
{
    public function all(QuestionBankDto $dto): object;

    public function find(int $id): object;

    public function create(QuestionBankDto $dto): object;

    // public function update(QuestionBankDto $dto, int $id): object;

    public function delete(int $id): object;
}
