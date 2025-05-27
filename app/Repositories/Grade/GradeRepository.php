<?php

namespace App\Repositories\Grade;

use App\Repositories\BaseRepository;
use App\Models\Grade\Grade;
use App\DataTransferObjects\Grade\GradeDto;
use Illuminate\Support\Facades\DB;

class GradeRepository extends BaseRepository implements GradeRepositoryInterface
{
    public function __construct(Grade $grade) {
        parent::__construct($grade);
    }

    public function all(GradeDto $dto): object
    {
        // return (object) $this->model->with('assignment', 'student')
        return (object) $this->model->with('student')
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
            // ->load('assignment', 'student');
            ->load('student');
    }

    public function create(GradeDto $dto): object
    {
        $grade = DB::transaction(function () use ($dto) {
            $grade = (object) $this->model->create([
                'assignment_id' => $dto->assignmentId,
                'student_id' => $dto->studentId,
                'due_date' => $dto->dueDate,
                'extended_due_date' => $dto->extendedDueDate,
                'status' => $dto->status,
                'points_earned' => $dto->pointsEarned,
                'max_points' => $dto->maxPoints,
                'percentage' => $dto->percentage,
                'category' => $dto->category,
                'class_average' => $dto->classAverage,
                'trend' => $dto->trend,
                'trend_data' => $dto->trendData,
                'feedback' => $dto->feedback,
                'resubmission' => $dto->resubmission,
                'resubmission_due' => $dto->resubmissionDue,
            ]);

            return $grade;
        });

        // return (object) $grade->load('assignment', 'student');
        return (object) $grade->load('student');
    }

    public function update(GradeDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $grade = DB::transaction(function () use ($dto, $model) {
            $grade = tap($model)->update([
                'due_date' => $dto->dueDate ? $dto->dueDate : $model->due_date,
                'extended_due_date' => $dto->extendedDueDate ? $dto->extendedDueDate : $model->extended_due_date,
                'status' => $dto->status ? $dto->status : $model->status,
                'points_earned' => $dto->pointsEarned ? $dto->pointsEarned : $model->points_earned,
                'max_points' => $dto->maxPoints ? $dto->maxPoints : $model->max_points,
                'percentage' => $dto->percentage ? $dto->percentage : $model->percentage,
                'category' => $dto->category ? $dto->category : $model->category,
                'class_average' => $dto->classAverage ? $dto->classAverage : $model->class_average,
                'trend' => $dto->trend ? $dto->trend : $model->trend,
                'trend_data' => $dto->trendData ? $dto->trendData : $model->trend_data,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
                'resubmission' => $dto->resubmission ? $dto->resubmission : $model->resubmission,
                'resubmission_due' => $dto->resubmissionDue ? $dto->resubmissionDue : $model->resubmission_due,
            ]);

            return $grade;
        });

        // return (object) $grade->load('assignment', 'student');
        return (object) $grade->load('student');
    }

    public function delete(int $id): object
    {
        $grade = DB::transaction(function () use ($id) {
            return parent::delete($id);
        });

        return (object) $grade;
    }
}
