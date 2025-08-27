<?php

namespace App\Repositories\Rubric;

use App\Repositories\BaseRepository;
use App\Models\Rubric\Rubric;
use App\DataTransferObjects\Rubric\RubricDto;
use Illuminate\Support\Facades\DB;

class RubricRepository extends BaseRepository implements RubricRepositoryInterface
{
    public function __construct(Rubric $rubric) {
        parent::__construct($rubric);
    }

    public function all(RubricDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $data['instructorId'])
            ->with('instructor', 'rubricCriterias', 'assignments')
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
        return (object) parent::find($id)
            ->load('instructor', 'rubricCriterias', 'assignments');
    }

    public function create(RubricDto $dto, array $data): object
    {
        $rubric = DB::transaction(function () use ($dto, $data) {
            $rubric = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'title' => $dto->title,
                'type' => $dto->type,
                'description' => $dto->description,
            ]);

            foreach ($dto->rubricCriterias as $rubricCriteria)
            {
                $rubric->rubricCriterias()->create([
                    'name' => $rubricCriteria['name'],
                    'weight' => $rubricCriteria['weight'],
                    'description' => $rubricCriteria['description'],
                    'levels' => $rubricCriteria['levels'],
                ]);
            }

            return $rubric;
        });

        return (object) $rubric->load('instructor', 'rubricCriterias', 'assignments');
    }

    public function update(RubricDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $rubric = DB::transaction(function () use ($dto, $model) {
            $rubric = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'type' => $dto->type ? $dto->type : $model->type,
                'description' => $dto->description ? $dto->description : $model->description,
            ]);

            if ($dto->rubricCriterias)
            {
                $rubric->rubricCriterias()->delete();

                foreach ($dto->rubricCriterias as $rubricCriteria)
                {
                    $rubric->rubricCriterias()->create([
                        'name' => $rubricCriteria['name'],
                        'weight' => $rubricCriteria['weight'],
                        'description' => $rubricCriteria['description'],
                        'levels' => $rubricCriteria['levels'],
                    ]);
                }
            }

            return $rubric;
        });

        return (object) $rubric->load('instructor', 'rubricCriterias', 'assignments');
    }

    public function delete(int $id): object
    {
        $rubric = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $rubric;
    }
}
