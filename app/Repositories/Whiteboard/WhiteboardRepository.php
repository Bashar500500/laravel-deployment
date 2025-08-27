<?php

namespace App\Repositories\Whiteboard;

use App\Repositories\BaseRepository;
use App\Models\Whiteboard\Whiteboard;
use App\DataTransferObjects\Whiteboard\WhiteboardDto;
use Illuminate\Support\Facades\DB;

class WhiteboardRepository extends BaseRepository implements WhiteboardRepositoryInterface
{
    public function __construct(Whiteboard $whiteboard) {
        parent::__construct($whiteboard);
    }

    public function all(WhiteboardDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $data['instructorId'])
            ->with('course')
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
            ->load('course');
    }

    public function create(WhiteboardDto $dto, array $data): object
    {
        $whiteboard = DB::transaction(function () use ($dto, $data) {
            $whiteboard = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'course_id' => $dto->courseId,
                'name' => $dto->name,
            ]);

            return $whiteboard;
        });

        return (object) $whiteboard->load('course');
    }

    public function update(WhiteboardDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $whiteboard = DB::transaction(function () use ($dto, $model) {
            $whiteboard = tap($model)->update([
                'course_id' => $dto->courseId ? $dto->courseId : $model->course_id,
                'name' => $dto->name ? $dto->name : $model->name,
            ]);

            return $whiteboard;
        });

        return (object) $whiteboard->load('course');
    }

    public function delete(int $id): object
    {
        $whiteboard = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $whiteboard;
    }
}
