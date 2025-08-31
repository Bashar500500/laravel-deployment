<?php

namespace App\Repositories\AssignmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\DataTransferObjects\AssignmentSubmit\AssignmentSubmitDto;
use App\Enums\AssignmentSubmit\AssignmentSubmitLevel;
use App\Enums\AssignmentSubmit\AssignmentSubmitStatus;
use App\Enums\Grade\GradeTrend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use App\Enums\Model\ModelTypePath;
use App\Enums\Plagiarism\PlagiarismStatus;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Trait\ModelName;

class InstructorAssignmentSubmitRepository extends BaseRepository implements AssignmentSubmitRepositoryInterface
{
    public function __construct(AssignmentSubmit $assignmentSubmit) {
        parent::__construct($assignmentSubmit);
    }

    public function all(AssignmentSubmitDto $dto, array $data): object
    {
        return (object) $this->model->where('assignment_id', $dto->assignmentId)
            ->with('assignment', 'student', 'attachments')
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
            ->load('assignment', 'student', 'attachments');
    }

    public function update(AssignmentSubmitDto $dto, int $id, array $data): object
    {
        $model = (object) parent::find($id);
        $points = 0;
        $detailedResults = [];
        $assignment = $model->assignment;
        $isPeerReviewed = $assignment->peer_review_settings['is_peer_reviewed'];

        if ($isPeerReviewed)
        {
            throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentPeerReviewed);
        }

        $rubric = $assignment->rubric;
        $grade = $assignment->grades->where('gradeable_type', ModelTypePath::Assignment->getTypePath())->where('gradeable_id', $assignment->id)->first();
        $grades = $assignment->grades->where('gradeable_type', ModelTypePath::Assignment->getTypePath())->where('gradeable_id', $assignment->id)->all();
        $gradeScoreSum = collect($grades)->sum('points_earned');

        $assignmentSubmit = DB::transaction(function () use ($dto, $model, $points, $detailedResults, $rubric, $grade, $assignment, $gradeScoreSum, $grades) {
            foreach ($dto->rubricCriterias as $item)
            {
                $rubricCriteria = $rubric->rubricCriterias->where('id', $item['rubric_criteria_id'])->first();

                if (! $rubricCriteria)
                {
                    throw CustomException::notFound('RubricCriteria');
                }

                switch ($item['level'])
                {
                    case AssignmentSubmitLevel::Excellent->getType():
                        $data = $this->calculateAssignmentPointsDependingOnLevel(
                            $rubricCriteria,
                            $assignment->points,
                            AssignmentSubmitLevel::Excellent->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    case AssignmentSubmitLevel::Good->getType():
                        $data = $this->calculateAssignmentPointsDependingOnLevel(
                            $rubricCriteria,
                            $assignment->points,
                            AssignmentSubmitLevel::Good->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    case AssignmentSubmitLevel::Satisfactory->getType():
                        $data = $this->calculateAssignmentPointsDependingOnLevel(
                            $rubricCriteria,
                            $assignment->points,
                            AssignmentSubmitLevel::Satisfactory->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    case AssignmentSubmitLevel::NeedsImprovement->getType():
                        $data = $this->calculateAssignmentPointsDependingOnLevel(
                            $rubricCriteria,
                            $assignment->points,
                            AssignmentSubmitLevel::NeedsImprovement->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    default:
                        $data = $this->calculateAssignmentPointsDependingOnLevel(
                            $rubricCriteria,
                            $assignment->points,
                            AssignmentSubmitLevel::Unsatisfactory->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                }
            }

            $assignmentSubmit = tap($model)->update([
                'status' => AssignmentSubmitStatus::Corrected,
                'score' => $model->score ? $model->score + $points : $points,
                'detailed_results' => $detailedResults,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('AssignmentSubmit/' . $assignmentSubmit->id . '/Files/' . $assignmentSubmit->student_id . '/Instructor',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $assignmentSubmit->attachment()->create([
                        'reference_field' => AttachmentReferenceField::AssignmentSubmitInstructorFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            $plagiarismCheck = $assignment->submission_settings['plagiarism_check'];
            if($plagiarismCheck)
            {
                $assignmentSubmit->plagiarism->update([
                    'score' => $dto->plagiarismScore,
                    'status' => $dto->plagiarismScore < 30 ? PlagiarismStatus::Clear : PlagiarismStatus::Flagged,
                ]);
            }

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

        return (object) $assignmentSubmit->load('assignment', 'student', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $assignmentSubmit = DB::transaction(function () use ($id, $model) {
            $attachments = $model->attachments;
            foreach ($attachments as $attachment)
            {
                $reference_field = $attachment->reference_field;
                switch ($reference_field)
                {
                    case AttachmentReferenceField::AssignmentSubmitInstructorFiles:
                        Storage::disk('supabase')->delete('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Instructor/' . $attachment?->url);
                        break;
                    default:
                        Storage::disk('supabase')->delete('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Student/' . $attachment?->url);
                        break;
                }
            }
            $model->attachments()->delete();
            return parent::delete($id);
        });

        return (object) $assignmentSubmit;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);
        $attachment = $model->attachments()->where('url', $fileName)->first();

        if (! $attachment)
        {
            throw CustomException::notFound('File');
        }

        $reference_field = $attachment->reference_field;
        switch ($reference_field)
        {
            case AttachmentReferenceField::AssignmentSubmitInstructorFiles:
                $exists = Storage::disk('supabase')->exists('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Instructor/' . $fileName);

                if (! $exists)
                {
                    throw CustomException::notFound('File');
                }

                $file = Storage::disk('supabase')->get('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Instructor/' . $fileName);
                break;
            default:
                $exists = Storage::disk('supabase')->exists('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Student/' . $fileName);

                if (! $exists)
                {
                    throw CustomException::notFound('File');
                }

                $file = Storage::disk('supabase')->get('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Student/' . $fileName);
                break;
        }

        $tempPath = storage_path('app/private/' . $fileName);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);
        $attachments = $model->attachments;

        if (count($attachments) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Assignment-Submit.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $reference_field = $attachment->reference_field;
                switch ($reference_field)
                {
                    case AttachmentReferenceField::AssignmentSubmitInstructorFiles:
                        $folder = 'Instructor';
                        $file = Storage::disk('supabase')->get('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Instructor/' . $attachment?->url);
                        break;
                    default:
                        $folder = 'Student';
                        $file = Storage::disk('supabase')->get('AssignmentSubmit/' . $model->id . '/Files/' . $model->student_id . '/Student/' . $attachment?->url);
                        break;
                }

                $tempPath = storage_path('app/private/' . $attachment?->url);
                file_put_contents($tempPath, $file);
                $zip->addFile($tempPath, $folder . '/' . $attachment?->url);
                $tempFiles[] = $tempPath;
            }
            $zip->close();
            File::delete($tempFiles);
        }

        return $zipPath;
    }

    private function calculateAssignmentPointsDependingOnLevel(object $rubricCriteria, int $assignmentPoints, string $level): array
    {
        $rubricCriteriaPointsToEarn = $rubricCriteria->levels[$level]['points'];
        $rubricCriteriaMaxPoints = $rubricCriteria->levels['excellent']['points'];
        $pointsEarned = ($rubricCriteriaPointsToEarn / 100) * $assignmentPoints;
        $pointsPossible = ($rubricCriteriaMaxPoints / 100) * $assignmentPoints;

        $result['criteria'] = $rubricCriteria->name;
        $result['level'] = $level;
        $result['description'] = $rubricCriteria->description;
        $result['points_earned'] = $pointsEarned;
        $result['points_possible'] = $pointsPossible;

        return [
            'points'=> $pointsEarned,
            'result'=> $result,
        ];
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
