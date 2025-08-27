<?php

namespace App\Repositories\ProjectSubmit;

use App\Repositories\BaseRepository;
use App\Models\ProjectSubmit\ProjectSubmit;
use App\DataTransferObjects\ProjectSubmit\ProjectSubmitDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Attachment\AttachmentReferenceField;

class StudentProjectSubmitRepository extends BaseRepository implements ProjectSubmitRepositoryInterface
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
        return (object) [];
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
}
