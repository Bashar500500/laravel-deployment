<?php

namespace App\Repositories\Policy;

use App\Repositories\BaseRepository;
use App\Models\Policy\Policy;
use App\DataTransferObjects\Policy\PolicyDto;
use Illuminate\Support\Facades\DB;

class PolicyRepository extends BaseRepository implements PolicyRepositoryInterface
{
    public function __construct(Policy $policy) {
        parent::__construct($policy);
    }

    public function all(PolicyDto $dto): object
    {
        return (object) $this->model->latest('created_at')
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
    }

    public function create(PolicyDto $dto): object
    {
        $policy = DB::transaction(function () use ($dto) {
            $policy = (object) $this->model->create([
                'name' => $dto->name,
                'category' => $dto->category,
                'description' => $dto->description,
            ]);

            return $policy;
        });

        return (object) $policy;
    }

    public function update(PolicyDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $policy = DB::transaction(function () use ($dto, $model) {
            $policy = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'category' => $dto->category ? $dto->category : $model->category,
                'description' => $dto->description ? $dto->description : $model->description,
            ]);

            return $policy;
        });

        return (object) $policy;
    }

    public function delete(int $id): object
    {
        $policy = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $policy;
    }
}
