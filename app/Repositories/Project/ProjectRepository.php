<?php

namespace App\Repositories\Project;

use App\Repositories\BaseRepository;
use App\Models\Project\Project;
use App\DataTransferObjects\Project\ProjectDto;
use App\DataTransferObjects\Project\ProjectSubmitDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Upload\UploadMessage;
use Illuminate\Support\Carbon;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\ProjectSubmit\ProjectSubmitStatus;
use App\Enums\Rubric\RubricType;
use App\Models\Rubric\Rubric;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project) {
        parent::__construct($project);
    }

    public function all(ProjectDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'leader', 'group', 'projectSubmits', 'attachments')
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
            ->load('course', 'leader', 'group', 'projectSubmits', 'attachments');
    }

    public function create(ProjectDto $dto): object
    {
        $rubric = Rubric::find($dto->rubricId);

        if ($rubric->type != RubricType::Project)
        {
            throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectRubric);
        }

        $project = DB::transaction(function () use ($dto) {
            $project = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'rubric_id' => $dto->rubricId,
                'leader_id' => $dto->leaderId,
                'group_id' => $dto->groupId,
                'name' => $dto->name,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'description' => $dto->description,
                'points' => $dto->points,
                'max_submits' => $dto->maxSubmits,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Project/' . $project->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $project->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ProjectFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            return $project;
        });

        return (object) $project->load('course', 'leader', 'group', 'projectSubmits', 'attachments');
    }

    public function update(ProjectDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        if ($dto->rubricId)
        {
            $rubric = Rubric::find($dto->rubricId);

            if ($rubric->type != RubricType::Project)
            {
                throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectRubric);
            }
        }

        $project = DB::transaction(function () use ($dto, $model) {
            $project = tap($model)->update([
                'rubric_id' => $dto->rubricId ? $dto->rubricId : $model->rubric_id,
                'name' => $dto->name ? $dto->name : $model->name,
                'start_date' => $dto->startDate ? $dto->startDate : $model->start_date,
                'end_date' => $dto->endDate ? $dto->endDate : $model->end_date,
                'description' => $dto->description ? $dto->description : $model->description,
                'points' => $dto->points ? $dto->points : $model->points,
                'max_submits' => $dto->maxSubmits ? $dto->maxSubmits : $model->max_submits,
            ]);

            if ($dto->files)
            {
                $attachments = $project->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Project/' . $project->id . '/Files/' . $attachment?->url);
                }
                $project->attachments()->delete();

                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Project/' . $project->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $project->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ProjectFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            return $project;
        });

        return (object) $project->load('course', 'leader', 'group', 'projectSubmits', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $project = DB::transaction(function () use ($id, $model) {
            $projectSubmits = $model->projectSubmits;

            foreach ($projectSubmits as $projectSubmit)
            {
                $attachments = $projectSubmit->attachments;
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
                $projectSubmit->attachments()->delete();
            }

            $attachments = $model->attachments;
            foreach ($attachments as $attachment)
            {
                Storage::disk('supabase')->delete('Project/' . $model->id . '/Files/' . $attachment?->url);
            }
            $model->attachments()->delete();
            return parent::delete($id);
        });

        return (object) $project;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Project/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $file = Storage::disk('supabase')->get('Project/' . $model->id . '/Files/' . $fileName);
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
        $zipName = 'Project-Files.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $file = Storage::disk('supabase')->get('Project/' . $model->id . '/Files/' . $attachment?->url);
                $tempPath = storage_path('app/private/' . $attachment?->url);
                file_put_contents($tempPath, $file);
                $zip->addFromString(basename($tempPath), file_get_contents($tempPath));
                $tempFiles[] = $tempPath;
            }
            $zip->close();
            File::delete($tempFiles);
        }

        return $zipPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            $storedFile = Storage::disk('supabase')->putFile('Project/' . $model->id . '/Files',
                $data['file']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $size = $data['file']->getSize();
            $sizeKb = round($size / 1024, 2);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::ProjectFiles,
                'type' => AttachmentType::File,
                'url' => basename($storedFile),
                'size_kb' => $sizeKb,
            ]);
        });

        return UploadMessage::File;
    }

    public function deleteAttachment(int $id, string $fileName): void
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Project/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $attachment = $model->attachments()->where('url', $fileName)->first();
        Storage::disk('supabase')->delete('Project/' . $model->id . '/Files/' . $attachment?->url);
        $model->attachments()->where('url', $fileName)->delete();
    }

    public function submit(ProjectSubmitDto $dto): object
    {
        $model = (object) parent::find($dto->projectId);
        $projectSubmits = $model->projectSubmits->count();
        $startDate = Carbon::parse($model->start_date);
        $endDate = Carbon::parse($model->end_date);

        if($projectSubmits == $model->max_sibmits)
        {
            throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectMaxSubmits);
        }

        if($startDate->isAfter(Carbon::today()) || $endDate->isBefore(Carbon::today()))
        {
            throw CustomException::forbidden(ModelName::Project, ForbiddenExceptionMessage::ProjectStartOrEndDate);
        }

        $projectSubmit = DB::transaction(function () use ($dto, $model) {
            $projectSubmit = $model->projectSubmits()->create([
                'status' => ProjectSubmitStatus::NotCorrected,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('ProjectSubmit/' . $projectSubmit->id . '/Files/Student',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $projectSubmit->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ProjectSubmitStudentFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            return $projectSubmit;
        });

        return (object) $projectSubmit;
    }
}
