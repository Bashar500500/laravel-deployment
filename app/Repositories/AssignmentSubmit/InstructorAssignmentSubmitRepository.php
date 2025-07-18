<?php

namespace App\Repositories\AssignmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\DataTransferObjects\AssignmentSubmit\AssignmentSubmitDto;
use App\Enums\Grade\GradeTrend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use App\Enums\Model\ModelTypePath;
use ZipArchive;

class InstructorAssignmentSubmitRepository extends BaseRepository implements AssignmentSubmitRepositoryInterface
{
    public function __construct(AssignmentSubmit $assignmentSubmit) {
        parent::__construct($assignmentSubmit);
    }

    public function all(AssignmentSubmitDto $dto, array $data): object
    {
        return (object) $this->model->where('assignment_id', $dto->assignmentId)
            ->with('attachments')
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
            ->load('attachments');
    }

    public function update(AssignmentSubmitDto $dto, int $id): object
    {
        $model = (object) parent::find($id);
        $assignment = $model->assignment;
        $grade = $model->grades->where('gradeable_type', ModelTypePath::Assignment->getTypePath())->where('gradeable_id', $assignment->id)->first();
        $grades = $model->grades->where('gradeable_type', ModelTypePath::Assignment->getTypePath())->where('gradeable_id', $assignment->id)->all();
        $gradeScoreSum = $grades->sum('points_earned');

        $assignmentSubmit = DB::transaction(function () use ($dto, $model, $grade, $assignment, $gradeScoreSum, $grades) {
            $assignmentSubmit = tap($model)->update([
                'score' => $dto->score ? $dto->score : $model->score,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            $oldTrendArray = $grade->trend_data;
            $newTrendArray = $oldTrendArray;
            array_push($newTrendArray, $assignmentSubmit->score);
            $trend = $this->calculateTrend($newTrendArray);

            $grade->update([
                'due_date' => $assignment->due_date,
                'points_earned' => $assignmentSubmit->score,
                'max_points' => $assignment->points,
                'percentage' => (1 / ($assignment->points / $assignmentSubmit->score)) * 100,
                'class_average' => ($gradeScoreSum / count($grades)),
                'trend' => $trend,
                'trend_data' => $newTrendArray,
                'resubmission_due' => $assignment->policies['late_submission']['cutoff_date'],
            ]);

            return $assignmentSubmit;
        });

        return (object) $assignmentSubmit->load('attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assignmentSubmit = DB::transaction(function () use ($id, $model) {
            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('AssignmentSubmit/' . $model->id);
            return parent::delete($id);
        });

        return (object) $assignmentSubmit;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $file = Storage::disk('local')->path('AssignmentSubmit/' . $id . '/Files/' . $model->student_id . '/' . $fileName);

        if (!file_exists($file))
        {
            throw CustomException::notFound('File');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $files = Storage::disk('local')->files('AssignmentSubmit/' . $id . '/Files/' . $model->student_id);

        if (count($files) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Assignment-Submit.zip';
        $zipPath = storage_path('app/private/' . $zipName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $path = Storage::disk('local')->path($file);
                $zip->addFromString(basename($path), file_get_contents($path));
            }
            $zip->close();
        }

        return $zipPath;
    }

    private function calculateTrend(array $values): GradeTrend
    {
        $average = array_sum($values) / count($values);

        if ($average >= 0 && $average <= 40) {
            $trend = GradeTrend::Down;
        } elseif ($average >= 41 && $average <= 59) {
            $trend = GradeTrend::Neutral;
        } elseif ($average >= 60 && $average <= 100) {
            $trend = GradeTrend::Up;
        }

        return $trend;
    }
}
