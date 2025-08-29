<?php

namespace App\Repositories\LearningRecommendation;

use App\Repositories\BaseRepository;
use App\Models\LearningRecommendation\LearningRecommendation;
use App\DataTransferObjects\LearningRecommendation\LearningRecommendationDto;
use Illuminate\Support\Facades\DB;

class LearningRecommendationRepository extends BaseRepository implements LearningRecommendationRepositoryInterface
{
    public function __construct(LearningRecommendation $learningRecommendation) {
        parent::__construct($learningRecommendation);
    }

    public function all(LearningRecommendationDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            // ->with('')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function find(int $id): object
    {
        return (object) parent::find($id);
            // ->load('');
    }

    public function create(LearningRecommendationDto $dto): object
    {
        $learningRecommendation = DB::transaction(function () use ($dto) {
            $learningRecommendation = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'gap_id' => $dto->gapId,
                'recommendation_type' => $dto->recommendationType,
                'resource_id' => $dto->resourceId,
                'resource_title' => $dto->resourceTitle,
                'resource_provider' => $dto->resourceProvider,
                'resource_url' => $dto->resourceUrl,
                'estimated_duration_hours' => $dto->estimatedDurationHours,
                'priority_order' => $dto->priorityOrder,
                'status' => $dto->status,
            ]);

            return $learningRecommendation;
        });

        return (object) $learningRecommendation;
            // ->load('instructor');
    }

    public function update(LearningRecommendationDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $learningRecommendation = DB::transaction(function () use ($dto, $model) {
            $learningRecommendation = tap($model)->update([
                'gap_id' => $dto->gapId ? $dto->gapId : $model->gap_id,
                'recommendation_type' => $dto->recommendationType ? $dto->recommendationType : $model->recommendation_type,
                'resource_id' => $dto->resourceId ? $dto->resourceId : $model->resource_id,
                'resource_title' => $dto->resourceTitle ? $dto->resourceTitle : $model->resource_title,
                'resource_provider' => $dto->resourceProvider ? $dto->resourceProvider : $model->resource_provider,
                'resource_url' => $dto->resourceUrl ? $dto->resourceUrl : $model->resource_url,
                'estimated_duration_hours' => $dto->estimatedDurationHours ? $dto->estimatedDurationHours : $model->estimated_duration_hours,
                'priority_order' => $dto->priorityOrder ? $dto->priorityOrder : $model->priority_order,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $learningRecommendation;
        });

        return (object) $learningRecommendation;
            // ->load('');
    }

    public function delete(int $id): object
    {
        $learningRecommendation = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $learningRecommendation;
    }
}
