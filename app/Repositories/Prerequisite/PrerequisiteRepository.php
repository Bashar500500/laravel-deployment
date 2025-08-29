<?php

namespace App\Repositories\Prerequisite;

use App\Repositories\BaseRepository;
use App\Models\Prerequisite\Prerequisite;
use App\DataTransferObjects\Prerequisite\PrerequisiteDto;
use Illuminate\Support\Facades\DB;
use App\Enums\Model\ModelTypePath;
use App\Enums\Prerequisite\PrerequisiteAppliesTo;
use App\Enums\Prerequisite\PrerequisiteType;

class PrerequisiteRepository extends BaseRepository implements PrerequisiteRepositoryInterface
{
    public function __construct(Prerequisite $prerequisite) {
        parent::__construct($prerequisite);
    }

    public function all(PrerequisiteDto $dto): object
    {
        return (object) $this->model
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

    public function create(PrerequisiteDto $dto, array $data): object
    {
        $prerequisite = DB::transaction(function () use ($dto, $data) {
            $prerequisite = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'type' => $dto->type,
                'prerequisiteable_type' => $dto->type == PrerequisiteType::Course ?
                    ModelTypePath::Course->getTypePath() :
                    ModelTypePath::Section->getTypePath(),
                'prerequisiteable_id' => $dto->prerequisite,
                'requiredable_type' => $dto->appliesTo == PrerequisiteAppliesTo::EntireCourse ?
                    ModelTypePath::Course->getTypePath() :
                    ModelTypePath::Section->getTypePath(),
                'requiredable_id' => $dto->requiredFor,
                'applies_to' => $dto->appliesTo,
                'condition' => $dto->condition,
                'allow_override' => $dto->allowOverride,
            ]);

            return $prerequisite;
        });

        return (object) $prerequisite;
            // ->load('');
    }

    public function update(PrerequisiteDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $prerequisite = DB::transaction(function () use ($dto, $model) {
            $prerequisite = tap($model)->update([
                'condition' => $dto->condition ? $dto->condition : $model->condition,
                'allow_override' => $dto->allowOverride ? $dto->allowOverride : $model->allow_override,
            ]);

            return $prerequisite;
        });

        return (object) $prerequisite;
            // ->load('');
    }

    public function delete(int $id): object
    {
        $prerequisite = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $prerequisite;
    }
}
