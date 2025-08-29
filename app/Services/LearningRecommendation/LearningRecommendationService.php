<?php

namespace App\Services\LearningRecommendation;

use App\Repositories\LearningRecommendation\LearningRecommendationRepositoryInterface;
use App\Http\Requests\LearningRecommendation\LearningRecommendationRequest;
use App\Models\LearningRecommendation\LearningRecommendation;
use App\DataTransferObjects\LearningRecommendation\LearningRecommendationDto;
use Illuminate\Support\Facades\Auth;

class LearningRecommendationService
{
    public function __construct(
        protected LearningRecommendationRepositoryInterface $repository,
    ) {}

    public function index(LearningRecommendationRequest $request): object
    {
        $dto = LearningRecommendationDto::fromIndexRequest($request);
        return $this->repository->all($dto);
    }

    public function show(LearningRecommendation $learningRecommendation): object
    {
        return $this->repository->find($learningRecommendation->id);
    }

    public function store(LearningRecommendationRequest $request): object
    {
        $dto = LearningRecommendationDto::fromStoreRequest($request);
        return $this->repository->create($dto);
    }

    public function update(LearningRecommendationRequest $request, LearningRecommendation $learningRecommendation): object
    {
        $dto = LearningRecommendationDto::fromUpdateRequest($request);
        return $this->repository->update($dto, $learningRecommendation->id);
    }

    public function destroy(LearningRecommendation $learningRecommendation): object
    {
        return $this->repository->delete($learningRecommendation->id);
    }
}
