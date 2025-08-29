<?php

namespace App\Repositories\ProjectSubmit;

use App\Repositories\BaseRepository;
use App\Models\ProjectSubmit\ProjectSubmit;
use App\DataTransferObjects\ProjectSubmit\ProjectSubmitDto;
use App\Enums\ProjectSubmit\ProjectSubmitLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\ProjectSubmit\ProjectSubmitStatus;

class InstructorProjectSubmitRepository extends BaseRepository implements ProjectSubmitRepositoryInterface
{
    public function __construct(ProjectSubmit $projectSubmit) {
        parent::__construct($projectSubmit);
    }

    public function all(ProjectSubmitDto $dto): object
    {
        return (object) $this->model->where('project_id', $dto->projectId)
            ->with('project', 'attachments')
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
            ->load('project', 'attachments');
    }

    public function update(ProjectSubmitDto $dto, int $id): object
    {
        $model = (object) parent::find($id);
        $points = 0;
        $detailedResults = [];
        $project = $model->project;
        $rubric = $project->rubric;

        $projectSubmit = DB::transaction(function () use ($dto, $model, $points, $detailedResults, $rubric, $project) {
            foreach ($dto->rubricCriterias as $item)
            {
                $rubricCriteria = $rubric->rubricCriterias->where('id', $item['rubric_criteria_id'])->first();

                if (! $rubricCriteria)
                {
                    throw CustomException::notFound('RubricCriteria');
                }

                switch ($item['level'])
                {
                    case ProjectSubmitLevel::Excellent->getType():
                        $data = $this->calculateProjectPointsDependingOnLevel(
                            $rubricCriteria,
                            $project->points,
                            ProjectSubmitLevel::Excellent->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    case ProjectSubmitLevel::Good->getType():
                        $data = $this->calculateProjectPointsDependingOnLevel(
                            $rubricCriteria,
                            $project->points,
                            ProjectSubmitLevel::Good->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    case ProjectSubmitLevel::Satisfactory->getType():
                        $data = $this->calculateProjectPointsDependingOnLevel(
                            $rubricCriteria,
                            $project->points,
                            ProjectSubmitLevel::Satisfactory->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    case ProjectSubmitLevel::NeedsImprovement->getType():
                        $data = $this->calculateProjectPointsDependingOnLevel(
                            $rubricCriteria,
                            $project->points,
                            ProjectSubmitLevel::NeedsImprovement->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                    default:
                        $data = $this->calculateProjectPointsDependingOnLevel(
                            $rubricCriteria,
                            $project->points,
                            ProjectSubmitLevel::Unsatisfactory->value);
                        $points += $data['points'];
                        array_push($detailedResults, $data['result']);
                        break;
                }
            }

            $projectSubmit = tap($model)->update([
                'status' => ProjectSubmitStatus::Corrected,
                'score' => $points,
                'detailed_results' => $detailedResults,
                'feedback' => $dto->feedback ? $dto->feedback : $model->feedback,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('ProjectSubmit/' . $projectSubmit->id . '/Files/Instructor',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $projectSubmit->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ProjectSubmitInstructorFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            return $projectSubmit;
        });

        return (object) $projectSubmit->load('project', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $projectSubmit = DB::transaction(function () use ($id, $model) {
            $attachments = $model->attachments;
            foreach ($attachments as $attachment)
            {
                $reference_field = $attachment->reference_field;
                switch ($reference_field)
                {
                    case AttachmentReferenceField::ProjectSubmitInstructorFiles:
                        Storage::disk('supabase')->delete('ProjectSubmit/' . $model->id . '/Files/Instructor/' . $attachment?->url);
                        break;
                    default:
                        Storage::disk('supabase')->delete('ProjectSubmit/' . $model->id . '/Files/Student/' . $attachment?->url);
                        break;
                }
            }
            $model->attachments()->delete();
            return parent::delete($id);
        });

        return (object) $projectSubmit;
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
            case AttachmentReferenceField::ProjectSubmitInstructorFiles:
                $exists = Storage::disk('supabase')->exists('ProjectSubmit/' . $model->id . '/Files/Instructor/' . $fileName);

                if (! $exists)
                {
                    throw CustomException::notFound('File');
                }

                $file = Storage::disk('supabase')->get('ProjectSubmit/' . $model->id . '/Files/Instructor/' . $fileName);
                break;
            default:
                $exists = Storage::disk('supabase')->exists('ProjectSubmit/' . $model->id . '/Files/Student/' . $fileName);

                if (! $exists)
                {
                    throw CustomException::notFound('File');
                }

                $file = Storage::disk('supabase')->get('ProjectSubmit/' . $model->id . '/Files/Student/' . $fileName);
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
        $zipName = 'Project-Submit.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $reference_field = $attachment->reference_field;
                switch ($reference_field)
                {
                    case AttachmentReferenceField::ProjectSubmitInstructorFiles:
                        $folder = 'Instructor';
                        $file = Storage::disk('supabase')->get('ProjectSubmit/' . $model->id . '/Files/Instructor/' . $attachment?->url);
                        break;
                    default:
                        $folder = 'Student';
                        $file = Storage::disk('supabase')->get('ProjectSubmit/' . $model->id . '/Files/Student/' . $attachment?->url);
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

    private function calculateProjectPointsDependingOnLevel(object $rubricCriteria, int $projectPoints, string $level): array
    {
        $rubricCriteriaPointsToEarn = $rubricCriteria->levels[$level]['points'];
        $rubricCriteriaMaxPoints = $rubricCriteria->levels['excellent']['points'];
        $pointsEarned = ($rubricCriteriaPointsToEarn / 100) * $projectPoints;
        $pointsPossible = ($rubricCriteriaMaxPoints / 100) * $projectPoints;

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
}
