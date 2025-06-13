<?php

namespace App\Services\Question;

use App\Repositories\Question\QuestionRepositoryInterface;
use App\Http\Requests\Question\QuestionRequest;
use App\Models\Question\Question;
use App\DataTransferObjects\Question\QuestionDto;

class QuestionService
{

    public function __construct(
        protected QuestionRepositoryInterface $repository,
    ) {}

    public function index(QuestionRequest $request): object
    {
        $dto = QuestionDto::fromIndexRequest($request);
        return match ($dto->category) {
            null => $this->repository->all($dto),
            default => $this->repository->allWithFilter($dto),
        };
    }

    public function show(Question $question): object
    {
        return $this->repository->find($question->id);
    }

    public function store(QuestionRequest $request): object
    {
        $dto = QuestionDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(QuestionRequest $request, Question $question): object
    {
        $dto = QuestionDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $question->id);
    }

    public function destroy(Question $question): object
    {
        return $this->repository->delete($question->id);
    }

    public function view(Question $question): string
    {
        return $this->repository->view($question->id);
    }

    public function download(Question $question): string
    {
        return $this->repository->download($question->id);
    }

    public function destroyAttachment(Question $question): void
    {
        $this->repository->deleteAttachment($question->id);
    }
}
