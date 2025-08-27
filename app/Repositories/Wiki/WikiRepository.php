<?php

namespace App\Repositories\Wiki;

use App\Repositories\BaseRepository;
use App\Models\Wiki\Wiki;
use App\DataTransferObjects\Wiki\WikiDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Upload\UploadMessage;

class WikiRepository extends BaseRepository implements WikiRepositoryInterface
{
    public function __construct(Wiki $wiki) {
        parent::__construct($wiki);
    }

    public function all(WikiDto $dto): object
    {
        return (object) $this->model
            // ->with('user', 'wikiComments', 'wikiRatings')
            ->with('user', 'attachments')
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
        // return (object) parent::find($id)->load('user', 'wikiComments', 'wikiRatings');
        return (object) parent::find($id)->load('user', 'attachments');
    }

    public function create(WikiDto $dto, array $data): object
    {
        $wiki = DB::transaction(function () use ($dto, $data) {
            $wiki = (object) $this->model->create([
                'user_id' => $data['userId'],
                'title' => $dto->title,
                'description' => $dto->description,
                'type' => $dto->type,
                'tags' => $dto->tags,
                'collaborators' => $dto->collaborators,
            ]);

            if ($dto->files)
            {
                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Wiki/' . $wiki->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $wiki->attachment()->create([
                        'reference_field' => AttachmentReferenceField::WikiFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            return $wiki;
        });

        // return (object) $wiki->load('user', 'wikiComments', 'wikiRatings');
        return (object) $wiki->load('user', 'attachments');
    }

    public function update(WikiDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $wiki = DB::transaction(function () use ($dto, $model) {
            $wiki = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'description' => $dto->description ? $dto->description : $model->description,
                'type' => $dto->type ? $dto->type : $model->type,
                'tags' => $dto->tags ? $dto->tags : $model->tags,
                'collaborators' => $dto->collaborators ? $dto->collaborators : $model->collaborators,
            ]);

            if ($dto->files)
            {
                $attachments = $wiki->attachments;
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Wiki/' . $wiki->id . '/Files/' . $attachment?->url);
                }
                $wiki->attachments()->delete();

                foreach ($dto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Wiki/' . $wiki->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $wiki->attachment()->create([
                        'reference_field' => AttachmentReferenceField::WikiFiles,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            return $wiki;
        });

        // return (object) $wiki->load('user', 'wikiComments', 'wikiRatings');
        return (object) $wiki->load('user', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $wiki = DB::transaction(function () use ($id, $model) {
            $attachments = $model->attachments;
            foreach ($attachments as $attachment)
            {
                Storage::disk('supabase')->delete('Wiki/' . $model->id . '/Files/' . $attachment?->url);
            }
            $model->attachments()->delete();
            return parent::delete($id);
        });

        return (object) $wiki;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Wiki/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $file = Storage::disk('supabase')->get('Wiki/' . $model->id . '/Files/' . $fileName);
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
        $zipName = 'Wiki-Files.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $file = Storage::disk('supabase')->get('Wiki/' . $model->id . '/Files/' . $attachment?->url);
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
            $storedFile = Storage::disk('supabase')->putFile('Wiki/' . $model->id . '/Files',
                $data['file']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::WikiFiles,
                'type' => AttachmentType::File,
                'url' => basename($storedFile),
                'size_kb' => $data['sizeKb'],
            ]);
        });

        return UploadMessage::File;
    }

    public function deleteAttachment(int $id, string $fileName): void
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Wiki/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $attachment = $model->attachments()->where('url', $fileName)->first();
        Storage::disk('supabase')->delete('Wiki/' . $model->id . '/Files/' . $attachment?->url);
        $model->attachments()->where('url', $fileName)->delete();
    }
}
