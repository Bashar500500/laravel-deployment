<?php

namespace App\Repositories\Event;

use App\Repositories\BaseRepository;
use App\Models\Event\Event;
use App\DataTransferObjects\Event\EventDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Enums\Upload\UploadMessage;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    public function __construct(Event $event) {
        parent::__construct($event);
    }

    public function all(EventDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->with('course', 'sectionEventGroups', 'attachments')
            ->latest('created_at')
            ->simplePaginate(
                $dto->pageSize,
                ['*'],
                'page',
                $dto->currentPage,
            );
    }

    public function allWithFilter(EventDto $dto): object
    {
        return (object) $this->model->where('course_id', $dto->courseId)
            ->where('recurrence', $dto->recurrence)
            ->with('course', 'sectionEventGroups', 'attachments')
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
            ->load('course', 'sectionEventGroups', 'attachments');
    }

    public function create(EventDto $dto): object
    {
        $event = DB::transaction(function () use ($dto) {
            $event = (object) $this->model->create([
                'course_id' => $dto->courseId,
                'name' => $dto->name,
                'type' => $dto->type,
                'date' => $dto->date,
                'start_time' => $dto->startTime,
                'end_time' => $dto->endTime,
                'category' => $dto->category,
                'recurrence' => $dto->recurrence,
                'description' => $dto->description,
            ]);

            if ($dto->groups)
            {
                foreach ($dto->groups as $id)
                {
                    $event->sectionEventGroups()->create([
                        'group_id' => $id,
                    ]);
                }
            }

            if ($dto->eventAttachmentsDto->files)
            {
                foreach ($dto->eventAttachmentsDto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Event/' . $event->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $event->attachment()->create([
                        'reference_field' => AttachmentReferenceField::EventAttachmentsFile,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            if ($dto->eventAttachmentsDto->links)
            {
                foreach ($dto->eventAttachmentsDto->links as $link)
                {
                    $event->attachment()->create([
                        'reference_field' => AttachmentReferenceField::EventAttachmentsLink,
                        'type' => AttachmentType::Link,
                        'url' => $link,
                        'size_kb' => 0.0,
                    ]);
                }
            }

            return $event;
        });

        return (object) $event->load('course', 'sectionEventGroups', 'attachments');
    }

    public function update(EventDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $event = DB::transaction(function () use ($dto, $model) {
            $event = tap($model)->update([
                'name' => $dto->name ? $dto->name : $model->name,
                'type' => $dto->type ? $dto->type : $model->type,
                'date' => $dto->date ? $dto->date : $model->date,
                'start_time' => $dto->startTime ? $dto->startTime : $model->start_time,
                'end_time' => $dto->endTime ? $dto->endTime : $model->end_time,
                'category' => $dto->category ? $dto->category : $model->category,
                'recurrence' => $dto->recurrence ? $dto->recurrence : $model->recurrence,
                'description' => $dto->description ? $dto->description : $model->description,
            ]);

            if ($dto->groups)
            {
                $event->sectionEventGroups()->delete();

                foreach ($dto->groups as $id)
                {
                    $event->sectionEventGroups()->create([
                        'group_id' => $id,
                    ]);
                }
            }

            if ($dto->eventAttachmentsDto->files)
            {
                $attachments = $event->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->all();
                foreach ($attachments as $attachment)
                {
                    Storage::disk('supabase')->delete('Event/' . $event->id . '/Files/' . $attachment?->url);
                }
                $event->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->delete();

                foreach ($dto->eventAttachmentsDto->files as $file)
                {
                    $storedFile = Storage::disk('supabase')->putFile('Event/' . $event->id . '/Files',
                        $file);

                    $size = $file->getSize();
                    $sizeKb = round($size / 1024, 2);

                    $event->attachment()->create([
                        'reference_field' => AttachmentReferenceField::EventAttachmentsFile,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
                        'size_kb' => $sizeKb,
                    ]);
                }
            }

            if ($dto->eventAttachmentsDto->links)
            {
                $event->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsLink)->delete();

                foreach ($dto->eventAttachmentsDto->links as $link)
                {
                    $event->attachment()->create([
                        'reference_field' => AttachmentReferenceField::EventAttachmentsLink,
                        'type' => AttachmentType::Link,
                        'url' => $link,
                        'size_kb' => 0.0,
                    ]);
                }
            }

            return $event;
        });

        return (object) $event->load('course', 'sectionEventGroups', 'attachments');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $event = DB::transaction(function () use ($id, $model) {
            $attachments = $model->attachments;
            foreach ($attachments as $attachment)
            {
                switch ($attachment->reference_field)
                {
                    case AttachmentReferenceField::EventAttachmentsFile:
                        Storage::disk('supabase')->delete('Event/' . $model->id . '/Files/' . $attachment?->url);
                        break;
                }
            }
            $model->attachments()->delete();
            $model->sectionEventGroups()->delete();
            return parent::delete($id);
        });

        return (object) $event;
    }

    public function view(int $id, string $fileName): string
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Event/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $file = Storage::disk('supabase')->get('Event/' . $model->id . '/Files/' . $fileName);
        $tempPath = storage_path('app/private/' . $fileName);
        file_put_contents($tempPath, $file);

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);
        $attachments = $model->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->get();

        if (count($attachments) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Event-Attachments.zip';
        $zipPath = storage_path('app/private/' . $zipName);
        $tempFiles = [];

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($attachments as $attachment) {
                $file = Storage::disk('supabase')->get('Event/' . $model->id . '/Files/' . $attachment?->url);
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
            $storedFile = Storage::disk('supabase')->putFile('Event/' . $model->id . '/Files',
                $data['file']);

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::EventAttachmentsFile,
                'type' => AttachmentType::File,
                'url' => basename($storedFile),
                'size_kb' => $data['sizeKb'],
            ]);
        });

        return UploadMessage::Image;
    }

    public function deleteAttachment(int $id, string $fileName): void
    {
        $model = (object) parent::find($id);

        $exists = Storage::disk('supabase')->exists('Event/' . $model->id . '/Files/' . $fileName);

        if (! $exists)
        {
            throw CustomException::notFound('File');
        }

        $attachment = $model->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->where('url', $fileName)->first();
        Storage::disk('supabase')->delete('Event/' . $model->id . '/Files/' . $attachment?->url);
        $model->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->where('url', $fileName)->delete();
    }
}
