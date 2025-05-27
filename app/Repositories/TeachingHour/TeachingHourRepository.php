<?php

namespace App\Repositories\TeachingHour;

use App\Repositories\BaseRepository;
use App\Models\TeachingHour\TeachingHour;
use App\DataTransferObjects\TeachingHour\TeachingHourDto;
use Illuminate\Support\Facades\DB;

class TeachingHourRepository extends BaseRepository implements TeachingHourRepositoryInterface
{
    public function __construct(TeachingHour $teachingHour) {
        parent::__construct($teachingHour);
    }

    public function all(TeachingHourDto $dto): object
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

    public function create(TeachingHourDto $dto): object
    {
        // check if instructor is instructor or student to throw an exception

        $teachingHour = DB::transaction(function () use ($dto) {
            $teachingHour = (object) $this->model->create([
                'instructor_id' => $dto->instructorId,
                'total_hours' => $dto->totalHours,
                'completed_hours' => $dto->completedHours,
                'upcoming' => $dto->upcoming,
                'break' => $dto->break,
                'status' => $dto->status,
            ]);

            return $teachingHour;
        });

        return (object) $teachingHour->load('instructor');
    }

    public function update(TeachingHourDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $teachingHour = DB::transaction(function () use ($dto, $model) {
            $teachingHour = tap($model)->update([
                'total_hours' => $dto->totalHours ? $dto->totalHours : $model->total_hours,
                'completed_hours' => $dto->completedHours ? $dto->completedHours : $model->completed_hours,
                'upcoming' => $dto->upcoming ? $dto->upcoming : $model->upcoming,
                'break' => $dto->break ? $dto->break : $model->break,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $teachingHour;
        });

        return (object) $teachingHour->load('instructor');
    }

    public function delete(int $id): object
    {
        $teachingHour = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $teachingHour;
    }
}
