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

class StudentProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project) {
        parent::__construct($project);
    }

    public function all(ProjectDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
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
        return (object) [];
    }

    public function update(ProjectDto $dto, int $id): object
    {
        return (object) [];
    }

    public function delete(int $id): object
    {
        return (object) [];
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
        return UploadMessage::File;
    }

    public function deleteAttachment(int $id, string $fileName): void {}

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
