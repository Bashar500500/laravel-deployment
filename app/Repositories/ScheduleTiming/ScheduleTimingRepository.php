<?php

namespace App\Repositories\ScheduleTiming;

use App\Repositories\BaseRepository;
use App\Models\ScheduleTiming\ScheduleTiming;
use App\DataTransferObjects\ScheduleTiming\ScheduleTimingDto;
use Illuminate\Support\Facades\DB;

class ScheduleTimingRepository extends BaseRepository implements ScheduleTimingRepositoryInterface
{
    public function __construct(ScheduleTiming $scheduleTiming) {
        parent::__construct($scheduleTiming);
    }

    public function all(ScheduleTimingDto $dto): object
    {
        return (object) $this->model->with('instructor', 'course')
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
            ->load('instructor', 'course');
    }

    public function create(ScheduleTimingDto $dto): object
    {
        $scheduleTiming = DB::transaction(function () use ($dto) {
            $scheduleTiming = (object) $this->model->create([
                'instructor_id' => $dto->instructorId,
                'course_id' => $dto->courseId,
                'instructor_available_timings' => $dto->instructorAvailableTimings,
            ]);

            return $scheduleTiming;
        });

        return (object) $scheduleTiming->load('instructor', 'course');
    }

    public function update(ScheduleTimingDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $scheduleTiming = DB::transaction(function () use ($dto, $model) {
            $scheduleTiming = tap($model)->update([
                'instructor_available_timings' => $dto->instructorAvailableTimings ? $dto->instructorAvailableTimings : $model->instructor_available_timings,
            ]);

            return $scheduleTiming;
        });

        return (object) $scheduleTiming->load('instructor', 'course');
    }

    public function delete(int $id): object
    {
        $scheduleTiming = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $scheduleTiming;
    }
}
