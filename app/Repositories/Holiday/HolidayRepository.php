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
        return (object) $this->model->with('instructor')
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
            ->load('instructor');
    }

    public function create(HolidayDto $dto, array $data): object
    {
        $holiday = DB::transaction(function () use ($dto, $data) {
            $holiday = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'title' => $dto->title,
                'date' => $dto->date,
                'day' => $dto->day,
            ]);

            return $holiday;
        });

        return (object) $holiday->load('instructor');
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

        return (object) $holiday->load('instructor');
    }

    public function delete(int $id): object
    {
        $holiday = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $holiday;
    }
}
