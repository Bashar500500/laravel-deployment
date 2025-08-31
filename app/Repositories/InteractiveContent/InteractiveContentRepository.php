<?php

namespace App\Repositories\InteractiveContent;

use App\Repositories\BaseRepository;
use App\Models\InteractiveContent\InteractiveContent;
use App\DataTransferObjects\InteractiveContent\InteractiveContentDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\InteractiveContent\InteractiveContentType;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class InteractiveContentRepository extends BaseRepository implements InteractiveContentRepositoryInterface
{
    public function __construct(InteractiveContent $interactiveContent) {
        parent::__construct($interactiveContent);
    }

    public function all(InteractiveContentDto $dto, array $data): object
    {
        return (object) $this->model->where('instructor_id', $data['instructorId'])
            ->with('attachment')
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
            ->load('attachment');
    }

    public function create(InteractiveContentDto $dto, array $data): object
    {
        $interactiveContent = DB::transaction(function () use ($dto, $data) {
            $interactiveContent = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'title' => $dto->title,
                'description' => $dto->description,
                'type' => $dto->type,
            ]);

            if (!is_null($dto->file))
            {
                switch ($dto->type)
                {
                    case InteractiveContentType::Video:
                        $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $interactiveContent->id . '/Videos',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $interactiveContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::InteractiveContentVideoFile,
                            'type' => AttachmentType::Video,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case InteractiveContentType::Presentation:
                        $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $interactiveContent->id . '/Presentations',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $interactiveContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::InteractiveContentPresentationFile,
                            'type' => AttachmentType::Presentation,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    default:
                        $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $interactiveContent->id . '/Quizzes',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $interactiveContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::InteractiveContentQuizFile,
                            'type' => AttachmentType::Quiz,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                }
            }

            return $interactiveContent;
        });

        return (object) $interactiveContent->load('attachment');
    }

    public function update(InteractiveContentDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $interactiveContent = DB::transaction(function () use ($dto, $model) {
            $interactiveContent = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'description' => $dto->description ? $dto->description : $model->description,
                'type' => $dto->type ? $dto->type : $model->type,
            ]);

            if (!is_null($dto->file))
            {

                switch ($dto->type)
                {
                    case InteractiveContentType::Video:
                        Storage::disk('supabase')->delete('InteractiveContent/' . $interactiveContent->id . '/Videos/' . $interactiveContent->attachment?->url);
                        $interactiveContent->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $interactiveContent->id . '/Videos',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $interactiveContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::InteractiveContentVideoFile,
                            'type' => AttachmentType::Video,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case InteractiveContentType::Presentation:
                        Storage::disk('supabase')->delete('InteractiveContent/' . $interactiveContent->id . '/Presentations/' . $interactiveContent->attachment?->url);
                        $interactiveContent->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $interactiveContent->id . '/Presentations',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $interactiveContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::InteractiveContentPresentationFile,
                            'type' => AttachmentType::Presentation,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    default:
                        Storage::disk('supabase')->delete('InteractiveContent/' . $interactiveContent->id . '/Quizzes/' . $interactiveContent->attachment?->url);
                        $interactiveContent->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $interactiveContent->id . '/Quizzes',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $interactiveContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::InteractiveContentQuizFile,
                            'type' => AttachmentType::Quiz,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                }
            }

            return $interactiveContent;
        });

        return (object) $interactiveContent->load('attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $interactiveContent = DB::transaction(function () use ($id, $model) {
            $attachment = $model?->attachment;
            switch ($attachment?->type)
            {
                case AttachmentType::Video:
                    Storage::disk('supabase')->delete('InteractiveContent/' . $model?->id . '/Videos/' . $attachment?->url);
                    break;
                case AttachmentType::Presentation:
                    Storage::disk('supabase')->delete('InteractiveContent/' . $model?->id . '/Presentations/' . $attachment?->url);
                    break;
                default:
                    Storage::disk('supabase')->delete('InteractiveContent/' . $model?->id . '/Quizzes/' . $attachment?->url);
                    break;
            }
            $model?->attachment()->delete();
            return parent::delete($id);
        });

        return (object) $interactiveContent;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        switch ($model->attachment->type)
        {
            case AttachmentType::Video:
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $model->id . '/Videos/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Video');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $model->id . '/Videos/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case AttachmentType::Presentation:
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $model->id . '/Presentations/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Presentation');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $model->id . '/Presentations/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            default:
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Quiz');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
        }

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        switch ($model->attachment->type)
        {
            case AttachmentType::Video:
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $model->id . '/Videos/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Video');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $model->id . '/Videos/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case AttachmentType::Presentation:
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $model->id . '/Presentations/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Presentation');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $model->id . '/Presentations/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            default:
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Quiz');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
        }

        return $tempPath;
    }

    public function upload(int $id, array $data): UploadMessage
    {
        $model = (object) parent::find($id);

        $message = DB::transaction(function () use ($data, $model) {
            switch ($model->type)
            {
                case InteractiveContentType::Video:
                    Storage::disk('supabase')->delete('InteractiveContent/' . $model->id . '/Videos/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $model->id . '/Videos',
                        $data['video']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::InteractiveContentVideoFile,
                        'type' => AttachmentType::Video,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Video;
                case InteractiveContentType::Presentation:
                    Storage::disk('supabase')->delete('InteractiveContent/' . $model->id . '/Presentations/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $model->id . '/Presentations',
                        $data['presentation']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::InteractiveContentPresentationFile,
                        'type' => AttachmentType::Presentation,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Presentation;
                default:
                    Storage::disk('supabase')->delete('InteractiveContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('InteractiveContent/' . $model->id . '/Quizzes',
                        $data['quiz']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::InteractiveContentQuizFile,
                        'type' => AttachmentType::Quiz,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Quiz;
            }
        });

        return $message;
    }

    public function deleteAttachment(int $id): void
    {
        $model = (object) parent::find($id);

        switch ($model->attachment->type)
        {
            case AttachmentType::Video:
                Storage::disk('supabase')->delete('InteractiveContent/' . $model->id . '/Videos/' . $model->attachment?->url);

                break;
            case AttachmentType::Presentation:
                Storage::disk('supabase')->delete('InteractiveContent/' . $model->id . '/Presentations/' . $model->attachment?->url);

                break;
            default:
                Storage::disk('supabase')->delete('InteractiveContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);

                break;
        }

        $model->attachments()->delete();
    }
}
