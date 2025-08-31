<?php

namespace App\Repositories\LearningActivity;

use App\Repositories\BaseRepository;
use App\Models\LearningActivity\LearningActivity;
use App\DataTransferObjects\LearningActivity\LearningActivityDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\LearningActivity\LearningActivityType;
use App\Enums\Attachment\AttachmentReferenceField;
use App\Enums\Attachment\AttachmentType;
use App\Enums\InteractiveContent\InteractiveContentType;
use App\Enums\ReusableContent\ReusableContentType;
use App\Exceptions\CustomException;
use App\Enums\Upload\UploadMessage;
use App\Models\InteractiveContent\InteractiveContent;
use App\Models\ReusableContent\ReusableContent;

class LearningActivityRepository extends BaseRepository implements LearningActivityRepositoryInterface
{
    public function __construct(LearningActivity $learningActivity) {
        parent::__construct($learningActivity);
    }

    public function all(LearningActivityDto $dto): object
    {
        return (object) $this->model->where('section_id', $dto->sectionId)
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

    public function create(LearningActivityDto $dto, array $data): object
    {
        $learningActivity = DB::transaction(function () use ($dto, $data) {
            $learningActivity = (object) $this->model->create([
                'section_id' => $dto->sectionId,
                'type' => $dto->type,
                'title' => $dto->title,
                'description' => $dto->description,
                'status' => $dto->status,
                'flags_is_free_preview' => $dto->learningActivityFlagsDto->isFreePreview,
                'flags_is_compulsory' => $dto->learningActivityFlagsDto->isCompulsory,
                'flags_requires_enrollment' => $dto->learningActivityFlagsDto->requiresEnrollment,
                'content_data' => $data['contentData'] ?? null,
                'thumbnail_url' => $dto->thumbnailUrl,
                'completion_type' => $dto->learningActivityCompletionDto->type,
                'completion_data' => $data['completionData'] ?? null,
                'availability_start' => $dto->learningActivityAvailabilityDto->start,
                'availability_end' => $dto->learningActivityAvailabilityDto->end,
                'availability_timezone' => $dto->learningActivityAvailabilityDto->timezone,
                'discussion_enabled' => $dto->learningActivityDiscussionDto->enabled,
                'discussion_moderated' => $dto->learningActivityDiscussionDto->moderated,
                'metadata_difficulty' => $dto->learningActivityMetadataDto->difficulty,
                'metadata_keywords' => $dto->learningActivityMetadataDto->keywords,
            ]);

            if (!is_null($dto->learningActivityContentDto->pdf) ||
                !is_null($dto->learningActivityContentDto->video) ||
                !is_null($dto->learningActivityContentDto->audio) ||
                !is_null($dto->learningActivityContentDto->word) ||
                !is_null($dto->learningActivityContentDto->powerPoint) ||
                !is_null($dto->learningActivityContentDto->zip))
            {
                switch ($dto->type)
                {
                    case LearningActivityType::Pdf:
                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Pdfs',
                            $dto->learningActivityContentDto->pdf);

                        $size = $dto->learningActivityContentDto->pdf->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityPdfContentFile,
                            'type' => AttachmentType::Pdf,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Audio:
                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Audios',
                            $dto->learningActivityContentDto->audio);

                        $size = $dto->learningActivityContentDto->audio->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityAudioContentFile,
                            'type' => AttachmentType::Audio,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Video:
                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Videos',
                            $dto->learningActivityContentDto->video);

                        $size = $dto->learningActivityContentDto->video->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityVideoContentFile,
                            'type' => AttachmentType::Video,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Word:
                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Words',
                            $dto->learningActivityContentDto->word);

                        $size = $dto->learningActivityContentDto->word->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityWordContentFile,
                            'type' => AttachmentType::Word,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::PowerPoint:
                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/PowerPoints',
                            $dto->learningActivityContentDto->powerPoint);

                        $size = $dto->learningActivityContentDto->powerPoint->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityPowerPointContentFile,
                            'type' => AttachmentType::PowerPoint,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Zip:
                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Zips',
                            $dto->learningActivityContentDto->zip);

                        $size = $dto->learningActivityContentDto->zip->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityZipContentFile,
                            'type' => AttachmentType::Zip,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                }
            }

            return $learningActivity;
        });

        return (object) $learningActivity->load('attachment');
    }

    public function update(LearningActivityDto $dto, array $data, int $id): object
    {
        $model = (object) parent::find($id);

        $learningActivity = DB::transaction(function () use ($dto, $data, $model) {
            $learningActivity = tap($model)->update([
                'type' => $dto->type ? $dto->type : $model->type,
                'title' => $dto->title ? $dto->title : $model->title,
                'description' => $dto->description ? $dto->description : $model->description,
                'status' => $dto->status ? $dto->status : $model->status,
                'flags_is_free_preview' => $dto->learningActivityFlagsDto->isFreePreview ? $dto->learningActivityFlagsDto->isFreePreview : $model->flags_is_free_preview,
                'flags_is_compulsory' => $dto->learningActivityFlagsDto->isCompulsory ? $dto->learningActivityFlagsDto->isCompulsory : $model->flags_is_compulsory,
                'flags_requires_enrollment' => $dto->learningActivityFlagsDto->requiresEnrollment ? $dto->learningActivityFlagsDto->requiresEnrollment : $model->flags_requires_enrollment,
                'content_data' => $data['contentData'] ? $data['contentData'] : $model->content_data,
                'thumbnail_url' => $dto->thumbnailUrl ? $dto->thumbnailUrl : $model->thumbnail_url,
                'completion_type' => $dto->learningActivityCompletionDto->type ? $dto->learningActivityCompletionDto->type : $model->completion_type,
                'completion_data' => $data['completionData'] ? $data['completionData'] : $model->completion_data,
                'availability_start' => $dto->learningActivityAvailabilityDto->start ? $dto->learningActivityAvailabilityDto->start : $model->availability_start,
                'availability_end' => $dto->learningActivityAvailabilityDto->end ? $dto->learningActivityAvailabilityDto->end : $model->availability_end,
                'availability_timezone' => $dto->learningActivityAvailabilityDto->timezone ? $dto->learningActivityAvailabilityDto->timezone : $model->availability_timezone,
                'discussion_enabled' => $dto->learningActivityDiscussionDto->enabled ? $dto->learningActivityDiscussionDto->enabled : $model->discussion_enabled,
                'discussion_moderated' => $dto->learningActivityDiscussionDto->moderated ? $dto->learningActivityDiscussionDto->moderated : $model->discussion_moderated,
                'metadata_difficulty' => $dto->learningActivityMetadataDto->difficulty ? $dto->learningActivityMetadataDto->difficulty : $model->metadata_difficulty,
                'metadata_keywords' => $dto->learningActivityMetadataDto->keywords ? $dto->learningActivityMetadataDto->keywords : $model->metadata_keywords,
            ]);

            if (!is_null($dto->learningActivityContentDto->pdf) ||
                !is_null($dto->learningActivityContentDto->video) ||
                !is_null($dto->learningActivityContentDto->audio) ||
                !is_null($dto->learningActivityContentDto->word) ||
                !is_null($dto->learningActivityContentDto->powerPoint) ||
                !is_null($dto->learningActivityContentDto->zip))
            {

                switch ($dto->type)
                {
                    case LearningActivityType::Pdf:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Pdfs/' . $learningActivity->attachment?->url);
                        $learningActivity->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Pdfs',
                            $dto->learningActivityContentDto->pdf);

                        $size = $dto->learningActivityContentDto->pdf->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityPdfContentFile,
                            'type' => AttachmentType::Pdf,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Audio:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Audios/' . $learningActivity->attachment?->url);
                        $learningActivity->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Audios',
                            $dto->learningActivityContentDto->audio);

                        $size = $dto->learningActivityContentDto->audio->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityAudioContentFile,
                            'type' => AttachmentType::Audio,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Video:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Videos/' . $learningActivity->attachment?->url);
                        $learningActivity->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Videos',
                            $dto->learningActivityContentDto->video);

                        $size = $dto->learningActivityContentDto->video->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityVideoContentFile,
                            'type' => AttachmentType::Video,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Word:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Words/' . $learningActivity->attachment?->url);
                        $learningActivity->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Words',
                            $dto->learningActivityContentDto->word);

                        $size = $dto->learningActivityContentDto->word->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityWordContentFile,
                            'type' => AttachmentType::Word,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::PowerPoint:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/PowerPoints/' . $learningActivity->attachment?->url);
                        $learningActivity->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/PowerPoints',
                            $dto->learningActivityContentDto->powerPoint);

                        $size = $dto->learningActivityContentDto->powerPoint->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityPowerPointContentFile,
                            'type' => AttachmentType::PowerPoint,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                    case LearningActivityType::Zip:
                        Storage::disk('supabase')->delete('LearningActivity/' . $learningActivity->id . '/Zips/' . $learningActivity->attachment?->url);
                        $learningActivity->attachments()->delete();

                        $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $learningActivity->id . '/Zips',
                            $dto->learningActivityContentDto->zip);

                        $size = $dto->learningActivityContentDto->zip->getSize();
                        $sizeKb = round($size / 1024, 2);

                        $learningActivity->attachment()->create([
                            'reference_field' => AttachmentReferenceField::LearningActivityZipContentFile,
                            'type' => AttachmentType::Zip,
                            'url' => basename($storedFile),
                            'size_kb' => $sizeKb,
                        ]);
                        break;
                }
            }

            return $learningActivity;
        });

        return (object) $learningActivity->load('attachment');
    }

    public function delete(int $id): object
    {
        $model = (object) parent::find($id);

        $learningActivity = DB::transaction(function () use ($id, $model) {
            $attachment = $model?->attachment;
            switch ($attachment?->type)
            {
                case AttachmentType::Pdf:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model?->id . '/Pdfs/' . $attachment?->url);
                    break;
                case AttachmentType::Audio:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model?->id . '/Audios/' . $attachment?->url);
                    break;
                case AttachmentType::Video:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model?->id . '/Videos/' . $attachment?->url);
                    break;
                case AttachmentType::Word:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model?->id . '/Words/' . $attachment?->url);
                    break;
                case AttachmentType::PowerPoint:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model?->id . '/PowerPoints/' . $attachment?->url);
                    break;
                case AttachmentType::Zip:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model?->id . '/Zips/' . $attachment?->url);
                    break;
            }
            $model?->attachment()->delete();
            return parent::delete($id);
        });

        return (object) $learningActivity;
    }

    public function view(int $id): string
    {
        $model = (object) parent::find($id);

        switch ($model->type)
        {
            case LearningActivityType::Pdf:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Pdfs/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Pdf');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Pdfs/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Audio:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Audios/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Audio');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Audios/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Video:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Videos/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Video');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Videos/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Word:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Words/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Word');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Words/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::PowerPoint:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/PowerPoints/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('PowerPoint');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/PowerPoints/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Zip:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Zips/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Zip');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Zips/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::InteractiveContent:
                $interactiveContent = InteractiveContent::find($model->content_data['interactiveContentId']);
                $tail = $interactiveContent->type == InteractiveContentType::Quiz ? 'zes' : 's';
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $interactiveContent->id . '/' . $interactiveContent->type->value . $tail . '/' . $interactiveContent->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('InteractiveContent');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $interactiveContent->id . '/' . $interactiveContent->type->value . $tail . '/' . $interactiveContent->attachment?->url);
                $tempPath = storage_path('app/private/' . $interactiveContent->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::ReusableContent:
                $reusableContent = ReusableContent::find($model->content_data['reusableContentId']);
                $tail = $reusableContent->type == ReusableContentType::Quiz ? 'zes' : 's';
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $reusableContent->id . '/' . $reusableContent->type->value . $tail . '/' . $reusableContent->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('ReusableContent');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $reusableContent->id . '/' . $reusableContent->type->value . $tail . '/' . $reusableContent->attachment?->url);
                $tempPath = storage_path('app/private/' . $reusableContent->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
        }

        return $tempPath;
    }

    public function download(int $id): string
    {
        $model = (object) parent::find($id);

        switch ($model->type)
        {
            case LearningActivityType::Pdf:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Pdfs/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Pdf');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Pdfs/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Audio:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Audios/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Audio');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Audios/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Video:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Videos/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Video');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Videos/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Word:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Words/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Word');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Words/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::PowerPoint:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/PowerPoints/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('PowerPoint');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/PowerPoints/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::Zip:
                $exists = Storage::disk('supabase')->exists('LearningActivity/' . $model->id . '/Zip/' . $model->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('Zip');
                }

                $file = Storage::disk('supabase')->get('LearningActivity/' . $model->id . '/Zip/' . $model->attachment?->url);
                $tempPath = storage_path('app/private/' . $model->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::InteractiveContent:
                $interactiveContent = InteractiveContent::find($model->content_data['interactiveContentId']);
                $tail = $interactiveContent->type == InteractiveContentType::Quiz ? 'zes' : 's';
                $exists = Storage::disk('supabase')->exists('InteractiveContent/' . $interactiveContent->id . '/' . $interactiveContent->type->value . $tail . '/' . $interactiveContent->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('InteractiveContent');
                }

                $file = Storage::disk('supabase')->get('InteractiveContent/' . $interactiveContent->id . '/' . $interactiveContent->type->value . $tail . '/' . $interactiveContent->attachment?->url);
                $tempPath = storage_path('app/private/' . $interactiveContent->attachment?->url);
                file_put_contents($tempPath, $file);

                break;
            case LearningActivityType::ReusableContent:
                $reusableContent = ReusableContent::find($model->content_data['reusableContentId']);
                $tail = $reusableContent->type == ReusableContentType::Quiz ? 'zes' : 's';
                $exists = Storage::disk('supabase')->exists('ReusableContent/' . $reusableContent->id . '/' . $reusableContent->type->value . $tail . '/' . $reusableContent->attachment?->url);

                if (! $exists)
                {
                    throw CustomException::notFound('ReusableContent');
                }

                $file = Storage::disk('supabase')->get('ReusableContent/' . $reusableContent->id . '/' . $reusableContent->type->value . $tail . '/' . $reusableContent->attachment?->url);
                $tempPath = storage_path('app/private/' . $reusableContent->attachment?->url);
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
                case LearningActivityType::Pdf:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Pdfs/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $model->id . '/Pdfs',
                        $data['pdf']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::LearningActivityPdfContentFile,
                        'type' => AttachmentType::Pdf,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Pdf;
                case LearningActivityType::Audio:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Audios/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $model->id . '/Audios',
                        $data['audio']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::LearningActivityAudioContentFile,
                        'type' => AttachmentType::Audio,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Audio;
                case LearningActivityType::Video:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Videos/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $model->id . '/Videos',
                        $data['video']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::LearningActivityVideoContentFile,
                        'type' => AttachmentType::Video,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Video;
                case LearningActivityType::Word:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Words/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $model->id . '/Words',
                        $data['word']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::LearningActivityWordContentFile,
                        'type' => AttachmentType::Word,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Word;
                case LearningActivityType::PowerPoint:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/PowerPoints/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $model->id . '/PowerPoints',
                        $data['powerPoint']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::LearningActivityPowerPointContentFile,
                        'type' => AttachmentType::PowerPoint,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::PowerPoint;
                case LearningActivityType::Zip:
                    Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Zips/' . $model->attachment?->url);
                    $model->attachments()->delete();

                    $storedFile = Storage::disk('supabase')->putFile('LearningActivity/' . $model->id . '/Zips',
                        $data['zip']);

                    array_map('unlink', glob("{$data['finalDir']}/*"));
                    rmdir($data['finalDir']);

                    $model->attachment()->create([
                        'reference_field' => AttachmentReferenceField::LearningActivityZipContentFile,
                        'type' => AttachmentType::Zip,
                        'url' => basename($storedFile),
                        'size_kb' => $data['sizeKb'],
                    ]);

                    return UploadMessage::Zip;
            }
        });

        return $message;
    }

    public function deleteAttachment(int $id): void
    {
        $model = (object) parent::find($id);

        switch ($model->attachment->type)
        {
            case AttachmentType::Pdf:
                Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Pdfs/' . $model->attachment?->url);

                break;
            case AttachmentType::Audio:
                Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Audios/' . $model->attachment?->url);

                break;
            case AttachmentType::Video:
                Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Videos/' . $model->attachment?->url);

                break;
            case AttachmentType::Word:
                Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Words/' . $model->attachment?->url);

                break;
            case AttachmentType::PowerPoint:
                Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/PowerPoints/' . $model->attachment?->url);

                break;
            case AttachmentType::Zip:
                Storage::disk('supabase')->delete('LearningActivity/' . $model->id . '/Zips/' . $model->attachment?->url);

                break;
        }

        $model->attachments()->delete();
    }
}
