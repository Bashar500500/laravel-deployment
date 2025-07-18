<?php

namespace App\Repositories\TimeLimit;

use App\Repositories\BaseRepository;
use App\Models\TimeLimit\TimeLimit;
use App\DataTransferObjects\TimeLimit\TimeLimitDto;
use Illuminate\Support\Facades\DB;

class TimeLimitRepository extends BaseRepository implements TimeLimitRepositoryInterface
{
    public function __construct(TimeLimit $timeLimit) {
        parent::__construct($timeLimit);
    }

    public function all(TimeLimitDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $data['instructorId'])
            // ->with('')
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
            // ->load('');
    }

    public function create(TimeLimitDto $dto, array $data): object
    {
        $timeLimit = DB::transaction(function () use ($dto, $data) {
            $timeLimit = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'name' => $dto->name,
                'description' => $dto->description,
                'status' => $dto->status,
                'duration_minutes' => $dto->durationMinutes,
                'type' => $dto->type,
                'grace_time_minutes' => $dto->graceTimeMinutes,
                'extension_time_minutes' => $dto->extensionTimeMinutes,
                'settings' => $dto->settings,
                'warnings' => $dto->warnings,
            ]);

            return $timeLimit;
        });

        return (object) $timeLimit;
            // ->load();
    }

    public function update(TimeLimitDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $timeLimit = DB::transaction(function () use ($dto, $model) {
            $timeLimit = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'description' => $dto->description ? $dto->description : $model->description,
                'status' => $dto->status ? $dto->status : $model->status,
                'duration_minutes' => $dto->durationMinutes ? $dto->durationMinutes : $model->duration_minutes,
                'type' => $dto->type ? $dto->type : $model->type,
                'grace_time_minutes' => $dto->graceTimeMinutes ? $dto->graceTimeMinutes : $model->grace_time_minutes,
                'extension_time_minutes' => $dto->extensionTimeMinutes ? $dto->extensionTimeMinutes : $model->extension_time_minutes,
                'settings' => $dto->settings ? $dto->settings : $model->settings,
                'warnings' => $dto->warnings ? $dto->warnings : $model->warnings,
            ]);

            return $timeLimit;
        });

        return (object) $timeLimit;
            // ->load();
    }

    public function delete(int $id): object
    {
        $timeLimit = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $timeLimit;
    }
}
