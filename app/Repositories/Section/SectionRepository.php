<?php

namespace App\Repositories\Section;

use App\Repositories\BaseRepository;
use App\Models\Section\Section;
use App\DataTransferObjects\Section\SectionDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use ZipArchive;
use App\Enums\Upload\UploadMessage;

class SectionRepository extends BaseRepository implements SectionRepositoryInterface
{
    public function __construct(Section $section) {
        parent::__construct($section);
    }

    public function all(SectionDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'groups', 'learningActivities', 'attachments')
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
            ->load('course', 'groups', 'learningActivities', 'attachments');
    }

    public function create(SectionDto $dto): object
    {
        $section = DB::transaction(function () use ($dto) {
            $section = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'title' => $dto->title,
                'description' => $dto->description,
                'status' => $dto->status,
                'access_release_date' => $dto->sectionAccessDto->releaseDate,
                'access_has_prerequest' => $dto->sectionAccessDto->hasPrerequest,
                'access_is_password_protected' => $dto->sectionAccessDto->isPasswordProtected,
                'access_password' => $dto->sectionAccessDto->isPasswordProtected ?
                    Hash::make($dto->sectionAccessDto->password) :
                    $dto->sectionAccessDto->password,
            ]);

            if ($dto->groups)
            {
                foreach ($dto->groups as $id)
                {
                    $section->sectionGroups()->create([
                        'group_id' => $id,
                    ]);
                }
            }

            if ($dto->sectionResourcesDto->files)
            {
                foreach ($dto->sectionResourcesDto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Section/' . $section->id . '/Files',
                        $file);

                    $section->attachment()->create([
                        'reference_field' => AttachmentReferenceField::SectionResourcesFile,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                    ]);
                }
            }

            if ($dto->sectionResourcesDto->links)
            {
                foreach ($dto->sectionResourcesDto->links as $link)
                {
                    $section->attachment()->create([
                        'reference_field' => AttachmentReferenceField::SectionResourcesLink,
                        'type' => AttachmentType::Link,
                        'url' => $link,
                    ]);
                }
            }

            return $section;
        });

        return (object) $section->load('course', 'groups', 'learningActivities', 'attachments');
    }

    public function update(SectionDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $section = DB::transaction(function () use ($dto, $model) {
            $section = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'description' => $dto->description ? $dto->description : $model->description,
                'status' => $dto->status ? $dto->status : $model->status,
                'access_release_date' => $dto->sectionAccessDto->releaseDate ? $dto->sectionAccessDto->releaseDate : $model->access_release_date,
                'access_has_prerequest' => $dto->sectionAccessDto->hasPrerequest ? $dto->sectionAccessDto->hasPrerequest : $model->access_has_prerequest,
                'access_is_password_protected' => $dto->sectionAccessDto->isPasswordProtected ? $dto->sectionAccessDto->isPasswordProtected : $model->access_is_password_protected,
                'access_password' => $dto->sectionAccessDto->password ? ($dto->sectionAccessDto->isPasswordProtected ?
                    Hash::make($dto->sectionAccessDto->password) :
                    $dto->sectionAccessDto->password) : $model->access_password,
            ]);

            if ($dto->groups)
            {
                $section->sectionGroups()->delete();

                foreach ($dto->groups as $id)
                {
                    $section->sectionGroups()->create([
                        'group_id' => $id,
                    ]);
                }
            }

            if ($dto->sectionResourcesDto->files)
            {
                $attachments = $section->attachments()->where('reference_field', AttachmentReferenceField::SectionResourcesFile)->all();
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Section/' . $section->id . '/Files/' . $attachment->url);
                }
                $attachments->delete();

                foreach ($dto->sectionResourcesDto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Section/' . $section->id . '/Files',
                        $file);

                    $section->attachment()->create([
                        'reference_field' => AttachmentReferenceField::SectionResourcesFile,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                    ]);
                }
            }

            if ($dto->sectionResourcesDto->links)
            {
                $section->attachments()->where('reference_field', AttachmentReferenceField::SectionResourcesLink)->delete();

                foreach ($dto->sectionResourcesDto->links as $link)
                {
                    $section->attachment()->create([
                        'reference_field' => AttachmentReferenceField::SectionResourcesLink,
                        'type' => AttachmentType::Link,
                        'url' => $link,
                    ]);
                }
            }

            return $section;
        });

        return (object) $section->load('course', 'groups', 'learningActivities', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $section = DB::transaction(function () use ($id, $model) {
            $learningActivities = $model->learningActivities;

            foreach ($learningActivities as $learningActivity)
            {
                $attachment = $learningActivity->attachment;
                switch ($attachment->type)
                {
                    case AttachmentType::Pdf:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Pdfs/' . $attachment->url);
                        break;
                    default:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Videos/' . $attachment->url);
                        break;
                }
                $attachment->delete();
            }

            $attachments = $model->attachments;
            foreach ($attachments as $attachment)
            {
                switch ($attachment->reference_field)
                {
                    case AttachmentReferenceField::SectionResourcesFile:
                        Storage::disk('supabase')->delete('Section/' . $model->id . '/Files/' . $attachment->url);
                        break;
                }
            }
            $attachments->delete();
            return parent::delete($id);
        });

        return (object) $section;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Section/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $file = Storage::disk('supabase')->get('Section/' . $model->id . '/Files/' . $fileName);
        $encoded = base64_encode($file);
        $decoded = base64_decode($encoded);
        $tempPath = storage_path('app/private/' . $fileName);
        file_put_contents($tempPath, $decoded);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);
        $attachments = $model->attachments()->where('reference_field', AttachmentReferenceField::SectionResourcesFile)->get();

        if (count($attachments) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Section-Resources.zip';
        $zipPath = storage_path('app/private/' . $zipName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $file = Storage::disk('supabase')->exists('Section/' . $model->id . '/Files/' . $attachment->url);
                $encoded = base64_encode($file);
                $decoded = base64_decode($encoded);
                $tempPath = storage_path('app/private/' . $attachment->url);
                file_put_contents($tempPath, $decoded);
                $zip->addFromString(basename($tempPath), file_get_contents($tempPath));
            }
            $zip->close();
        }

        return $zipPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            $storedFile = Storage::disk('supabase')->putFile('Section/' . $model->id . '/Files',
                $data['file']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::SectionResourcesFile,
                'type' => AttachmentType::File,
                'url' => basename($storedFile),
            ]);
        });

        return UploadMessage::File;
    }

    public function deleteAttachment(int $id, string $fileName): void
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Section/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $attachment = $model->attachments()->where('reference_field', AttachmentReferenceField::SectionResourcesFile)->where('url', $fileName)->first();
        Storage::disk('supabase')->delete('Section/' . $model->id . '/Files/' . $attachment->url);
        $attachment->delete();
    }
}
