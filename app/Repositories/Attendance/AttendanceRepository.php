<?php

namespace App\Repositories\Attendance;

use App\Repositories\BaseRepository;
use App\Models\Attendance\Attendance;
use App\DataTransferObjects\Attendance\AttendanceDto;
use Illuminate\Support\Facades\DB;
use App\Enums\Trait\ModelName;
use App\Exceptions\CustomException;
use App\Enums\Exception\ForbiddenExceptionMessage;

class AttendanceRepository extends BaseRepository implements AttendanceRepositoryInterface
{
    public function __construct(Attendance $attendance) {
        parent::__construct($attendance);
    }

    public function all(AttendanceDto $dto): object
    {
        return (object) $this->model->with('section', 'student')
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
            ->load('section', 'student');
    }

    public function create(AttendanceDto $dto): object
    {
        $exists = $this->model->where('section_id', $dto->sectionId)
            ->where('student_id', $dto->studentId)->first();

        if ($exists)
        {
            throw CustomException::forbidden(ModelName::Attendance, ForbiddenExceptionMessage::Attendance);
        }

        $attendance = DB::transaction(function () use ($dto) {
            $attendance = (object) $this->model->create([
                'section_id' => $dto->sectionId,
                'student_id' => $dto->studentId,
                'is_present' => $dto->isPresent,
            ]);

            return $attendance;
        });

        return (object) $attendance->load('section', 'student');
    }

    public function update(AttendanceDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $attendance = DB::transaction(function () use ($dto, $model) {
            $attendance = tap($model)->update([
                'is_present' => $dto->isPresent ? $dto->isPresent : $model->is_present,
            ]);

            return $attendance;
        });

        return (object) $attendance->load('section', 'student');
    }

    public function delete(int $id): object
    {
        $attendance = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $attendance;
    }
}
