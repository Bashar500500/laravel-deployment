<?php

namespace App\Repositories\Holiday;

use App\Repositories\BaseRepository;
use App\Models\Holiday\Holiday;
use App\DataTransferObjects\Holiday\HolidayDto;
use Illuminate\Support\Facades\DB;

class HolidayRepository extends BaseRepository implements HolidayRepositoryInterface
{
    public function __construct(Holiday $holiday) {
        parent::__construct($holiday);
    }

    public function all(HolidayDto $dto): object
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

    public function create(HolidayDto $dto): object
    {
        $holiday = DB::transaction(function () use ($dto) {
            $holiday = (object) $this->model->create([
                'title' => $dto->title,
                'date' => $dto->date,
                'day' => $dto->day,
            ]);

            return $holiday;
        });

        return (object) $holiday;
    }

    public function update(HolidayDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $holiday = DB::transaction(function () use ($dto, $model) {
            $holiday = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'date' => $dto->date ? $dto->date : $model->date,
                'day' => $dto->day ? $dto->day : $model->day,
            ]);

            return $holiday;
        });

        return (object) $holiday;
    }

    public function delete(int $id): object
    {
        $holiday = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $holiday;
    }
}
