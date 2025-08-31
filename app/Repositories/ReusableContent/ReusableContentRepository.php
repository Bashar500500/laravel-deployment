<?php

namespace App\Repositories\ReusableContent;

use App\Repositories\BaseRepository;
use App\Models\ReusableContent\ReusableContent;
use App\DataTransferObjects\ReusableContent\ReusableContentDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\ReusableContent\ReusableContentType;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;

class ReusableContentRepository extends BaseRepository implements ReusableContentRepositoryInterface
{
    public function __construct(ReusableContent $reusableContent) {
        parent::__construct($reusableContent);
    }

    public function all(ReusableContentDto $dto, array $data): object
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

    public function create(ReusableContentDto $dto, array $data): object
    {
        $reusableContent = DB::transaction(function () use ($dto, $data) {
            $reusableContent = (object) $this->model->create([
                'instructor_id' => $data['instructorId'],
                'title' => $dto->title,
                'description' => $dto->description,
                'type' => $dto->type,
                'tags' => $dto->tags,
                'share_with_community' => $dto->shareWithCommunity,
            ]);

            if (!is_null($dto->file))
            {
                switch ($dto->type)
                {
                    case ReusableContentType::Video:
                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Videos',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentVideoFile,
                            'type' => AttachmentType::Video,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case ReusableContentType::Presentation:
                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Presentations',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentPresentationFile,
                            'type' => AttachmentType::Presentation,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case ReusableContentType::Pdf:
                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Pdfs',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentPdfFile,
                            'type' => AttachmentType::Pdf,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    default:
                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Quizzes',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentQuizFile,
                            'type' => AttachmentType::Quiz,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                }
            }

            return $reusableContent;
        });

        return (object) $reusableContent->load('attachment');
    }

    public function update(ReusableContentDto $dto, int $id): object
    {
        $model = (object) parent::find($id);

        $reusableContent = DB::transaction(function () use ($dto, $model) {
            $reusableContent = tap($model)->update([
                'title' => $dto->title ? $dto->title : $model->title,
                'description' => $dto->description ? $dto->description : $model->description,
                'type' => $dto->type ? $dto->type : $model->type,
                'tags' => $dto->tags ? $dto->tags : $model->tags,
                'share_with_community' => $dto->shareWithCommunity ? $dto->shareWithCommunity : $model->share_with_community,
            ]);

            if (!is_null($dto->file))
            {

                switch ($dto->type)
                {
                    case ReusableContentType::Video:
                        Storage::disk('supabase')->delete('ReusableContent/' . $reusableContent->id . '/Videos/' . $reusableContent->attachment?->url);
                        $reusableContent->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Videos',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentVideoFile,
                            'type' => AttachmentType::Video,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case ReusableContentType::Presentation:
                        Storage::disk('supabase')->delete('ReusableContent/' . $reusableContent->id . '/Presentations/' . $reusableContent->attachment?->url);
                        $reusableContent->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Presentations',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentPresentationFile,
                            'type' => AttachmentType::Presentation,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case ReusableContentType::Pdf:
                        Storage::disk('supabase')->delete('ReusableContent/' . $reusableContent->id . '/Pdfs/' . $reusableContent->attachment?->url);
                        $reusableContent->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Pdfs',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentPdfFile,
                            'type' => AttachmentType::Pdf,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    default:
                        Storage::disk('supabase')->delete('ReusableContent/' . $reusableContent->id . '/Quizzes/' . $reusableContent->attachment?->url);
                        $reusableContent->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $reusableContent->id . '/Quizzes',
                            $dto->file);

                        $size = $dto->file->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $reusableContent->attachment()->create([
                            'reference_field' => AttachmentReferenceField::ReusableContentQuizFile,
                            'type' => AttachmentType::Quiz,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                }
            }

            return $reusableContent;
        });

        return (object) $reusableContent->load('attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $reusableContent = DB::transaction(function () use ($id, $model) {
            $attachment = $model?->attachment;
            switch ($attachment?->type)
            {
                case AttachmentType::Video:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model?->id . '/Videos/' . $attachment?->url);
                    break;
                case AttachmentType::Presentation:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model?->id . '/Presentations/' . $attachment?->url);
                    break;
                case AttachmentType::Pdf:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model?->id . '/Pdfs/' . $attachment?->url);
                    break;
                default:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model?->id . '/Quizzes/' . $attachment?->url);
                    break;
            }
            $model?->attachment()->delete();
            return parent::delete($id);
        });

        return (object) $reusableContent;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        switch ($model->attachment->type)
        {
            case AttachmentType::Video:
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Videos/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Video');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Videos/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case AttachmentType::Presentation:
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Presentations/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Presentation');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Presentations/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case AttachmentType::Pdf:
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Pdfs/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Pdf');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Pdf/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            default:
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Quiz');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);
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
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Videos/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Video');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Videos/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case AttachmentType::Presentation:
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Presentations/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Presentation');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Presentations/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case AttachmentType::Pdf:
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Pdfs/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Pdf');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Pdfs/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            default:
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Quiz');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);
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
                case ReusableContentType::Video:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Videos/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $model->id . '/Videos',
                        $data['video']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ReusableContentVideoFile,
                        'type' => AttachmentType::Video,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Video;
                case ReusableContentType::Presentation:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Presentations/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $model->id . '/Presentations',
                        $data['presentation']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ReusableContentPresentationFile,
                        'type' => AttachmentType::Presentation,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Presentation;
                case ReusableContentType::Pdf:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Pdfs/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $model->id . '/Pdfs',
                        $data['pdf']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ReusableContentPdfFile,
                        'type' => AttachmentType::Pdf,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Pdf;
                default:
                    Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('ReusableContent/' . $model->id . '/Quizzes',
                        $data['quiz']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::ReusableContentQuizFile,
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
                Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Videos/' . $model->attachment?->url);

                break;
            case AttachmentType::Presentation:
                Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Presentations/' . $model->attachment?->url);

                break;
            case AttachmentType::Pdf:
                Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Pdfs/' . $model->attachment?->url);

                break;
            default:
                Storage::disk('supabase')->delete('ReusableContent/' . $model->id . '/Quizzes/' . $model->attachment?->url);

                break;
        }

        $model->attachments()->delete();
    }
}
