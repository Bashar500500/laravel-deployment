<?php

namespace App\Repositories\Project;

use App\Repositories\BaseRepository;
use App\Models\Project\Project;
use App\DataTransferObjects\Project\ProjectDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use ZipArchive;
use App\Enums\Upload\UploadMessage;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project) {
        parent::__construct($project);
    }

    public function all(ProjectDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'leader', 'group', 'attachments')
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
            ->load('course', 'leader', 'group', 'attachments');
    }

    public function create(ProjectDto $dto): object
    {
        $project = DB::transaction(function () use ($dto) {
            $project = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'leader_id' => $dto->leaderId,
                'group_id' => $dto->groupId,
                'name' => $dto->name,
                'start_date' => $dto->startDate,
                'end_date' => $dto->endDate,
                'description' => $dto->description,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    match (is_null($file->extension())) {
                        true => $storedFile = Storage::disk('local')->putFileAs('Project/' . $project->id . '/Files',
                                $file,
                                str()->uuid() . '.txt'),
                        false => $storedFile = Storage::disk('local')->putFileAs('Project/' . $project->id . '/Files',
                                $file,
                                str()->uuid() . '.' . $file->extension()),
                    };

                    $project->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ProjectFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                    ]);
                }
            }

            return $project;
        });

        return (object) $project->load('course', 'leader', 'group', 'attachments');
    }

    public function update(ProjectDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $project = DB::transaction(function () use ($dto, $model) {
            $project = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'start_date' => $dto->startDate ? $dto->startDate : $model->start_date,
                'end_date' => $dto->endDate ? $dto->endDate : $model->end_date,
                'description' => $dto->description ? $dto->endDate : $model->description,
            ]);

            if ($dto->files)
            {
                $project->attachments()->delete();
                Storage::disk('local')->deleteDirectory('Project/' . $project->id);

                foreach ($dto->files as $file)
                {
                    match (is_null($file->extension())) {
                        true => $storedFile = Storage::disk('local')->putFileAs('Project/' . $project->id . '/Files',
                                $file,
                                str()->uuid() . '.txt'),
                        false => $storedFile = Storage::disk('local')->putFileAs('Project/' . $project->id . '/Files',
                                $file,
                                str()->uuid() . '.' . $file->extension()),
                    };

                    $project->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ProjectFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                    ]);
                }
            }

            return $project;
        });

        return (object) $project->load('course', 'leader', 'group', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $project = DB::transaction(function () use ($id, $model) {
            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Project/' . $model->id);
            return parent::delete($id);
        });

        return (object) $project;
    }

    public function view(int $id, string $fileName): string
    {
        $file = Storage::disk('local')->path('Project/' . $id . '/Files/' . $fileName);

        if (!file_exists($file))
        {
            throw CustomException::notFound('File');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $files = Storage::disk('local')->files('Project/' . $id . '/Files');

        if (count($files) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Project-Files.zip';
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
            $storedFile = Storage::disk('local')->putFileAs('Project/' . $model->id . '/Files',
                $data['file'],
                basename($data['file']));

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::ProjectFiles,
                'type' => AttachmentType::File,
                'url' => basename($storedFile),
            ]);
        });

        return UploadMessage::File;
    }

    public function deleteAttachment(int $id, string $fileName): void
    {
        $model = (object) parent::find($id);
        $model->attachments()->where('url', $fileName)->delete();
        Storage::disk('local')->delete('Project/' . $model->id . '/Files/' . $fileName);
    }
}
