<?php

namespace App\Repositories\Leave;

use App\Repositories\BaseRepository;
use App\Models\Leave\Leave;
use App\DataTransferObjects\Leave\LeaveDto;
use Illuminate\Support\Facades\DB;

class LeaveRepository extends BaseRepository implements LeaveRepositoryInterface
{
    public function __construct(Leave $leave) {
        parent::__construct($leave);
    }

    public function all(LeaveDto $dto): object
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
        return (object) parent::find($id)->load('instructor');
    }

    public function create(LeaveDto $dto, array $data): object
    {
        $leave = DB::transaction(function () use ($dto, $data) {
            $leave = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'type' => $dto->type,
                'from' => $dto->from,
                'to' => $dto->to,
                'number_of_days' => $dto->numberOfDays,
                'reason' => $dto->reason,
                'status' => $dto->status,
            ]);

            return $leave;
        });

        return (object) $leave->load('instructor');
    }

    public function update(LeaveDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $leave = DB::transaction(function () use ($dto, $model) {
            $leave = tap($model)->update([
                'type' => $dto->type ? $dto->type : $model->type,
                'from' => $dto->from ? $dto->from : $model->from,
                'to' => $dto->to ? $dto->to : $model->to,
                'number_of_days' => $dto->numberOfDays ? $dto->numberOfDays : $model->number_of_days,
                'reason' => $dto->reason ? $dto->reason : $model->reason,
                'status' => $dto->status ? $dto->status : $model->status,
            ]);

            return $leave;
        });

        return (object) $leave->load('instructor');
    }

    public function delete(int $id): object
    {
        $leave = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $leave;
    }
}
