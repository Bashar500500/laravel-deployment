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
                    match (is_null($file->extension())) {
                        true => $storedFile = Storage::disk('local')->putFileAs('Section/' . $section->id . '/Files',
                                $file,
                                str()->uuid() . '.txt'),
                        false => $storedFile = Storage::disk('local')->putFileAs('Section/' . $section->id . '/Files',
                                $file,
                                str()->uuid() . '.' . $file->extension()),
                    };

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
                $section->attachments()->where('reference_field', AttachmentReferenceField::SectionResourcesFile)->delete();
                Storage::disk('local')->deleteDirectory('Section/' . $section->id);

                foreach ($dto->sectionResourcesDto->files as $file)
                {
                    match (is_null($file->extension())) {
                        true => $storedFile = Storage::disk('local')->putFileAs('Section/' . $section->id . '/Files',
                                $file,
                                str()->uuid() . '.txt'),
                        false => $storedFile = Storage::disk('local')->putFileAs('Section/' . $section->id . '/Files',
                                $file,
                                str()->uuid() . '.' . $file->extension()),
                    };

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
                $learningActivity->attachments()->delete();
                Storage::disk('local')->deleteDirectory('LearningActivity/' . $learningActivity->id);
            }

            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Section/' . $model->id);
            return parent::delete($id);
        });

        return (object) $section;
    }

    public function view(int $id, string $fileName): string
    {
        $file = Storage::disk('local')->path('Section/' . $id . '/Files/' . $fileName);

        if (!file_exists($file))
        {
            throw CustomException::notFound('File');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $files = Storage::disk('local')->files('Section/' . $id . '/Files');

        if (count($files) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Section-Resources.zip';
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

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        DB::transaction(function () use ($data, $model) {
            $storedFile = Storage::disk('local')->putFileAs('Section/' . $model->id . '/Files',
                $data['file'],
                basename($data['file']));

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
        $model->attachments()->where('reference_field', AttachmentReferenceField::SectionResourcesFile)->where('url', $fileName)->delete();
        Storage::disk('local')->delete('Section/' . $model->id . '/Files/' . $fileName);
    }
}
