<?php

namespace App\Repositories\AssignmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\DataTransferObjects\AssignmentSubmit\AssignmentSubmitDto;
use App\Enums\AssignmentSubmit\AssignmentSubmitLevel;
use App\Enums\AssignmentSubmit\AssignmentSubmitStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Grade\GradeTrend;
use App\Enums\Model\ModelTypePath;
use App\Enums\Plagiarism\PlagiarismStatus;
use App\Enums\Trait\ModelName;

class StudentAssignmentSubmitRepository extends BaseRepository implements AssignmentSubmitRepositoryInterface
{
    public function __construct(AssignmentSubmit $assignmentSubmit) {
        parent::__construct($assignmentSubmit);
    }

    public function all(AssignmentSubmitDto $dto, $data): object
    {
        return (object) $this->model->where('assignment_id', $dto->assignmentId)
            ->where('student_id', $data['studentId'])
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

    public function update(AssignmentSubmitDto $dto, int $id, array $authData): object
    {
        $model = (object) parent::find($id);
        $points = 0;
        $detailedResults = [];
        $assignment = $model->assignment;
        $isPeerReviewed = $assignment->peer_review_settings['is_peer_reviewed'];

        if ($isPeerReviewed)
        {
            throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentNotPeerReviewed);
        }

        $rubric = $assignment->rubric;
        $grade = $model->grades->where('gradeable_type', ModelTypePath::Assignment->getTypePath())->where('gradeable_id', $assignment->id)->first();
        $grades = $model->grades->where('gradeable_type', ModelTypePath::Assignment->getTypePath())->where('gradeable_id', $assignment->id)->all();
        $gradeScoreSum = $grades->sum('points_earned');

        $assignmentSubmit = DB::transaction(function () use ($dto, $model, $authData, $points, $detailedResults, $rubric, $grade, $assignment, $gradeScoreSum, $grades) {
            if ($model->status == AssignmentSubmitStatus::NotCorrected)
            {
                $correctionsCount = 0;
                $penalty = $model->score ? $model->score : 0;
            }
            else
            {
                $exists = collect($model->detailed_results)->contains('studentId', $authData['studentId']);

                if ($exists)
                {
                    throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentCorrectedByStudent);
                }

                $correctionsCount = count($model->detailed_results);
                $penalty = $model->detailed_results[0]['penalty'];
            }

            if ($correctionsCount == $assignment->peer_review_settings['allowed_reviews'])
            {
                throw CustomException::forbidden(ModelName::Assignment, ForbiddenExceptionMessage::AssignmentAllowedReviews);
            }

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

            $detailedResults[0]['penalty'] = $penalty;
            $detailedResults[0]['studentId'] = $authData['studentId'];
            $isLastCorrection = $correctionsCount == ($assignment->peer_review_settings['allowed_reviews']) - 1;
            $assignmentSubmit = tap($model)->update([
                'status' => $isLastCorrection ? AssignmentSubmitStatus::Corrected : AssignmentSubmitStatus::Pending,
                'score' => $correctionsCount == 0 ? $points :
                    ($isLastCorrection ? $detailedResults[0]['penalty'] + (($model->points + $points) / ($correctionsCount + 1)) :
                    ($model->points + $points) / ($correctionsCount + 1)),
                'detailed_results' => $model->detailed_results ?
                    array_push($model->detailed_results, $detailedResults[0]) :
                    $detailedResults,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            $plagiarismCheck = $assignment->submission_settings['plagiarism_check'];
            if($plagiarismCheck)
            {
                $plagiarism = $assignmentSubmit->plagiarism;
                $plagiarism->update([
                    'score' => $correctionsCount == 0 ? $dto->plagiarismScore : ($plagiarism->score + $dto->plagiarismScore) / ($correctionsCount + 1),
                    'status' => $isLastCorrection ?
                        ($dto->plagiarismScore < 30 ? PlagiarismStatus::Clear : PlagiarismStatus::Flagged) :
                        PlagiarismStatus::Pending,
                ]);
            }

            if ($isLastCorrection)
            {
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
            }

            return $assignmentSubmit;
        });

        return (object) $assignmentSubmit->load('assignment', 'student', 'attachments');
    }

    public function delete(int $id): object
    {
        return (object) [];
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
