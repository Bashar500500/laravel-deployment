<?php

namespace App\Repositories\LearningGap;

use App\Repositories\BaseRepository;
use App\Models\LearningGap\LearningGap;
use App\DataTransferObjects\LearningGap\LearningGapDto;
use Illuminate\Support\Facades\DB;

class LearningGapRepository extends BaseRepository implements LearningGapRepositoryInterface
{
    public function __construct(LearningGap $learningGap) {
        parent::__construct($learningGap);
    }

    public function all(LearningGapDto $dto): object
    {
        return (object) $this->model->where('student_id', $dto->studentId)
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

    public function create(LearningGapDto $dto): object
    {
        $learningGap = DB::transaction(function () use ($dto) {
            $learningGap = (object) $this->model->create([
                'student_id' => $dto->studentId,
                'skill_id' => $dto->skillId,
                'target_role' => $dto->targetRole,
                'current_level' => $dto->currentLevel,
                'required_level' => $dto->requiredLevel,
                'gap_size' => $dto->gapSize,
                'priority' => $dto->priority,
                'gap_score' => $dto->gapScore,
                'status' => $dto->status,
            ]);

            return $learningGap;
        });

        return (object) $learningGap;
            // ->load('instructor');
    }

    public function update(LearningGapDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $learningGap = DB::transaction(function () use ($dto, $model) {
            $learningGap = tap($model)->update([
                'skill_id' => $dto->skillId ? $dto->skillId : $model->skill_id,
                'target_role' => $dto->targetRole ? $dto->targetRole : $model->target_role,
                'current_level' => $dto->currentLevel ? $dto->currentLevel : $model->current_level,
                'required_level' => $dto->requiredLevel ? $dto->requiredLevel : $model->required_level,
                'gap_size' => $dto->gapSize ? $dto->gapSize : $model->gap_size,
                'priority' => $dto->priority ? $dto->priority : $model->priority,
                'gap_score' => $dto->gapScore ? $dto->gapScore : $model->gap_score,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $learningGap;
        });

        return (object) $learningGap;
            // ->load('');
    }

    public function delete(int $id): object
    {
        $learningGap = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $learningGap;
    }
}
