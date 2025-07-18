<?php

namespace App\Services\QuestionBank;

use App\Repositories\QuestionBank\QuestionBankRepositoryInterface;
use App\Http\Requests\QuestionBank\QuestionBankRequest;
use App\Models\QuestionBank\QuestionBank;
use App\DataTransferObjects\QuestionBank\QuestionBankDto;

class QuestionBankService
{
    public function __construct(
        protected QuestionBankRepositoryInterface $repository,
    ) {}

    public function index(QuestionBankRequest $request): object
    {
        $dto = QuestionBankDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(QuestionBank $questionBank): object
    {
        return $this->repository->find($questionBank->id);
    }

    public function store(QuestionBankRequest $request): object
    {
        $dto = QuestionBankDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    // public function update(QuestionBankRequest $request, QuestionBank $questionBank): object
    // {
    //     $dto = QuestionBankDto::fromUpdateRequest($request);
    //     return $this->repository->update($dto, $questionBank->id);
    // }

    public function destroy(QuestionBank $questionBank): object
    {
        return $this->repository->delete($questionBank->id);
    }
}
