<?php

namespace App\Repositories\Progress;

use App\Repositories\BaseRepository;
use App\Models\Progress\Progress;
use App\DataTransferObjects\Progress\ProgressDto;
use Illuminate\Support\Facades\DB;

class ProgressRepository extends BaseRepository implements ProgressRepositoryInterface
{
    public function __construct(Progress $progress) {
        parent::__construct($progress);
    }

    public function all(ProgressDto $dto): object
    {
        return (object) $this->model->with('course', 'student')
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
            ->load('course', 'student');
    }

    public function create(ProgressDto $dto): object
    {
        $progress = DB::transaction(function () use ($dto) {
            $progress = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'student_id' => $dto->studentId,
                'progress' => $dto->progress,
                'modules' => $dto->modules,
                'last_active' => $dto->lastActive,
                'streak' => $dto->streak,
                'skill_level' => $dto->skillLevel,
                'upcomig' => $dto->upcomig,
            ]);

            return $progress;
        });

        return (object) $progress->load('course', 'student');
    }

    public function update(ProgressDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $progress = DB::transaction(function () use ($dto, $model) {
            $progress = tap($model)->update([
                'progress' => $dto->progress ? $dto->progress : $model->progress,
                'modules' => $dto->modules ? $dto->modules : $model->modules,
                'last_active' => $dto->lastActive ? $dto->lastActive : $model->last_active,
                'streak' => $dto->streak ? $dto->streak : $model->streak,
                'skill_level' => $dto->skillLevel ? $dto->skillLevel : $model->skill_level,
                'upcomig' => $dto->upcomig ? $dto->upcomig : $model->upcomig,
            ]);

            return $progress;
        });

        return (object) $progress->load('course', 'student');
    }

    public function delete(int $id): object
    {
        $progress = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $progress;
    }
}
