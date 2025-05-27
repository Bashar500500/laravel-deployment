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
use App\Enums\Upload\UploadMessage;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    public function __construct(Event $event) {
        parent::__construct($event);
    }

    public function all(EventDto $dto): object
    {
        return (object) $this->model->with('course')
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
        return (object) $this->model->where('recurrence', $dto->recurrence)
            ->with('course')
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
            ->load('course');
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

            if ($dto->eventAttachmentsDto->files)
            {
                foreach ($dto->eventAttachmentsDto->files as $file)
                {
                    match (is_null($file->extension())) {
                        true => $storedFile = Storage::disk('local')->putFileAs('Event/' . $event->id . '/Files',
                                $file,
                                str()->uuid() . '.txt'),
                        false => $storedFile = Storage::disk('local')->putFileAs('Event/' . $event->id . '/Files',
                                $file,
                                str()->uuid() . '.' . $file->extension()),
                    };

                    $event->attachment()->create([
                        'reference_field' => AttachmentReferenceField::EventAttachmentsFile,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
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
                    ]);
                }
            }

            return $event;
        });

        return (object) $event->load('course');
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

            if ($dto->eventAttachmentsDto->files)
            {
                $event->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->delete();
                Storage::disk('local')->deleteDirectory('Event/' . $event->id);

                foreach ($dto->eventAttachmentsDto->files as $file)
                {
                    match (is_null($file->extension())) {
                        true => $storedFile = Storage::disk('local')->putFileAs('Event/' . $event->id . '/Files',
                                $file,
                                str()->uuid() . '.txt'),
                        false => $storedFile = Storage::disk('local')->putFileAs('Event/' . $event->id . '/Files',
                                $file,
                                str()->uuid() . '.' . $file->extension()),
                    };

                    $event->attachment()->create([
                        'reference_field' => AttachmentReferenceField::EventAttachmentsFile,
                        'type' => AttachmentType::File,
                        'url' => basename($storedFile),
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
                    ]);
                }
            }

            return $event;
        });

        return (object) $event->load('course');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $event = DB::transaction(function () use ($id, $model) {
            $model->attachments()->delete();
            Storage::disk('local')->deleteDirectory('Event/' . $model->id);
            return parent::delete($id);
        });

        return (object) $event;
    }

    public function view(int $id, string $fileName): string
    {
        $file = Storage::disk('local')->path('Event/' . $id . '/Files/' . $fileName);

        if (!file_exists($file))
        {
            throw CustomException::notFound('File');
        }

        return $file;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        $files = Storage::disk('local')->files('Event/' . $id . '/Files');

        if (count($files) == 0)
        {
            throw CustomException::notFound('Files');
        }

        $zip = new ZipArchive();
        $zipName = 'Event-Attachments.zip';
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
            $storedFile = Storage::disk('local')->putFileAs('Event/' . $model->id . '/Files',
                $data['file'],
                basename($data['file']));

            array_map('unlink', glob("{$data['finalDir']}/*"));
            rmdir($data['finalDir']);

            $model->attachment()->create([
                'reference_field' => AttachmentReferenceField::EventAttachmentsFile,
                'type' => AttachmentType::File,
                'url' => basename($storedFile),
            ]);
        });

        return UploadMessage::Image;
    }

    public function deleteAttachment(int $id, string $fileName): void
    {
        $model = (object) parent::find($id);
        $model->attachments()->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->where('url', $fileName)->delete();
        Storage::disk('local')->delete('Event/' . $model->id . '/Files/' . $fileName);
    }
}
