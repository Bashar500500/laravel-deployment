<?php

namespace App\Services\LearningActivity;

use App\Repositories\LearningActivity\LearningActivityRepositoryInterface;
use App\Http\Requests\LearningActivity\LearningActivityRequest;
use App\Models\LearningActivity\LearningActivity;
use App\DataTransferObjects\LearningActivity\LearningActivityDto;
use App\Enums\LearningActivity\LearningActivityCompletionType;
use App\Enums\LearningActivity\LearningActivityType;

class LearningActivityService
{
    public function __construct(
        protected LearningActivityRepositoryInterface $repository,
    ) {}

    public function index(LearningActivityRequest $request): object
    {
        $dto = LearningActivityDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(LearningActivity $learningActivity): object
    {
        return $this->repository->find($learningActivity->id);
    }

    public function store(LearningActivityRequest $request): object
    {
        $dto = LearningActivityDto::fromStoreRequest($request);
        $data = $this->prepareStoreAndUpdateData($dto);
        return $this->repository->create($dto, $data);
    }

    public function update(LearningActivityRequest $request, LearningActivity $learningActivity): object
    {
        $dto = LearningActivityDto::fromUpdateRequest($request);
        $data = $this->prepareStoreAndUpdateData($dto);
        return $this->repository->update($dto, $data, $learningActivity->id);
    }

    public function destroy(LearningActivity $learningActivity): object
    {
        return $this->repository->delete($learningActivity->id);
    }

    public function view(LearningActivity $learningActivity): string
    {
        return $this->repository->view($learningActivity->id);
    }

    public function download(LearningActivity $learningActivity): string
    {
        return $this->repository->download($learningActivity->id);
    }

    public function destroyAttachment(LearningActivity $learningActivity): void
    {
        $this->repository->deleteAttachment($learningActivity->id);
    }

    private function prepareStoreAndUpdateData(LearningActivityDto $dto): array
    {
        $data = [];

        switch ($dto->type)
        {
            case LearningActivityType::LiveSession:
                $data['contentData']['captions']['url'] = $dto->learningActivityContentDto->learningActivityContentCaptionsDto->url;
                break;
            case LearningActivityType::InteractiveContent:
                $data['contentData']['interactiveContentId'] = $dto->learningActivityContentDto->interactiveContentId;
                break;
            case LearningActivityType::ReusableContent:
                $data['contentData']['reusableContentId'] = $dto->learningActivityContentDto->reusableContentId;
                break;
        }

        switch ($dto->learningActivityCompletionDto->type)
        {
            case LearningActivityCompletionType::View:
                $data['completionData']['minDuration'] = $dto->learningActivityCompletionDto->minDuration;
                break;
            case LearningActivityCompletionType::Score:
                $data['completionData']['passingScore'] = $dto->learningActivityCompletionDto->passingScore;
                break;
            default:
                $data['completionData']['rules'] = $dto->learningActivityCompletionDto->rules;
                break;
        }

        return $data;
    }
}
