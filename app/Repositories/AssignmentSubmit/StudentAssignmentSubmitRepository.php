<?php

namespace App\Repositories\AssignmentSubmit;

use App\Repositories\BaseRepository;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use App\DataTransferObjects\AssignmentSubmit\AssignmentSubmitDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Attachment\AttachmentReferenceField;

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

    public function update(AssignmentSubmitDto $dto, int $id): object
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
}
