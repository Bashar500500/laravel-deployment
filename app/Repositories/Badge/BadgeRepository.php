<?php

namespace App\Repositories\Badge;

use App\Repositories\BaseRepository;
use App\Models\Badge\Badge;
use App\DataTransferObjects\Badge\BadgeDto;
use Illuminate\Support\Facades\DB;

class BadgeRepository extends BaseRepository implements BadgeRepositoryInterface
{
    public function __construct(Badge $badge) {
        parent::__construct($badge);
    }

    public function all(BadgeDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $data['instructorId'])
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

    public function create(BadgeDto $dto, array $data): object
    {
        $badge = DB::transaction(function () use ($dto, $data) {
            $badge = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'name' => $dto->name,
                'description' => $dto->description,
                'category' => $dto->category,
                'sub_category' => $dto->subCategory,
                'difficulty' => $dto->difficulty,
                'icon' => $dto->icon,
                'color' => $dto->color,
                'shape' => $dto->shape,
                'image_url' => $dto->imageUrl,
                'status' => $dto->status,
                'reward' => $dto->reward,
            ]);

            return $badge;
        });

        return (object) $badge;
            // ->load('userAwards');
    }

    public function update(BadgeDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $badge = DB::transaction(function () use ($dto, $model) {
            $badge = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'description' => $dto->description ? $dto->description : $model->description,
                'category' => $dto->category ? $dto->category : $model->category,
                'sub_category' => $dto->subCategory ? $dto->subCategory : $model->sub_category,
                'difficulty' => $dto->difficulty ? $dto->difficulty : $model->difficulty,
                'icon' => $dto->icon ? $dto->icon : $model->icon,
                'color' => $dto->color ? $dto->color : $model->color,
                'shape' => $dto->shape ? $dto->shape : $model->shape,
                'image_url' => $dto->imageUrl ? $dto->imageUrl : $model->image_url,
                'status' => $dto->status ? $dto->status : $model->status,
                'reward' => $dto->reward ? $dto->reward : $model->reward,
            ]);

            return $badge;
        });

        return (object) $badge;
            // ->load('userAwards');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $badge = DB::transaction(function () use ($id, $model) {
            $model->challengeRuleBadges()->delete();
            return parent::delete($id);
        });

        return (object) $badge;
    }
}
