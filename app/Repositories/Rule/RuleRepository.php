<?php

namespace App\Repositories\Rule;

use App\Repositories\BaseRepository;
use App\Models\Rule\Rule;
use App\DataTransferObjects\Rule\RuleDto;
use Illuminate\Support\Facades\DB;

class RuleRepository extends BaseRepository implements RuleRepositoryInterface
{
    public function __construct(Rule $rule) {
        parent::__construct($rule);
    }

    public function all(RuleDto $dto): object
    {
        return (object) $this->model
            // ->with()
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
            // ->load();
    }

    public function create(RuleDto $dto): object
    {
        $rule = DB::transaction(function () use ($dto) {
            $rule = (object) $this->model->create([
                'name' => $dto->name,
                'description' => $dto->description,
                'category' => $dto->category,
                'points' => $dto->points,
                'frequency' => $dto->frequency,
                'status' => $dto->status,
            ]);

            return $rule;
        });

        return (object) $rule;
            // ->load();
    }

    public function update(RuleDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $rule = DB::transaction(function () use ($dto, $model) {
            $rule = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'description' => $dto->description ? $dto->description : $model->description,
                'category' => $dto->category ? $dto->category : $model->category,
                'points' => $dto->points ? $dto->points : $model->points,
                'frequency' => $dto->frequency ? $dto->frequency : $model->frequency,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $rule;
        });

        return (object) $rule;
            // ->load();
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $rule = DB::transaction(function () use ($id, $model) {
            $model->challengeRuleBadges()->delete();
            return parent::delete($id);
        });

        return (object) $rule;
    }
}
