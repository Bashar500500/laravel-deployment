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

    public function create(LeaveDto $dto): object
    {
        $leave = DB::transaction(function () use ($dto) {
            $leave = (object) $this->model->create([
                'type' => $dto->type,
                'from' => $dto->from,
                'to' => $dto->to,
                'number_of_days' => $dto->numberOfDays,
                'reason' => $dto->reason,
                'status' => $dto->status,
            ]);

            return $leave;
        });

        return (object) $leave;
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

        return (object) $leave;
    }

    public function delete(int $id): object
    {
        $leave = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $leave;
    }
}
