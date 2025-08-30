<?php

namespace App\DataTransferObjects\Upload;

use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Http\Requests\Upload\Content\LearningActivityContentUploadRequest;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Models\LearningActivity\LearningActivity;
use Illuminate\Http\UploadedFile;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\InteractiveContent\InteractiveContentType;
use App\Enums\LearningActivity\LearningActivityType;
use App\Enums\ReusableContent\ReusableContentType;
use App\Http\Requests\Upload\Content\InteractiveContentContentUploadRequest;
use App\Http\Requests\Upload\Content\ReusableContentContentUploadRequest;
use App\Models\InteractiveContent\InteractiveContent;
use App\Models\ReusableContent\ReusableContent;

class UploadDto
{
    public function __construct(
        public readonly ?UploadedFile $image,
        public readonly ?UploadedFile $pdf,
        public readonly ?UploadedFile $video,
        public readonly ?UploadedFile $audio,
        public readonly ?UploadedFile $word,
        public readonly ?UploadedFile $powerPoint,
        public readonly ?UploadedFile $zip,
        public readonly ?UploadedFile $file,
        public readonly ?UploadedFile $presentation,
        public readonly ?UploadedFile $quiz,
        public readonly ?string $dzuuid,
        public readonly ?int $dzChunkIndex,
        public readonly ?int $dzTotalChunkCount,
    ) {}

    public static function fromImageUploadRequest(ImageUploadRequest $request): UploadDto
    {
        return new self(
            image: $request->validated('image') ? UploadedFile::createFromBase($request->validated('image')) : null,
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            file: null,
            presentation: null,
            quiz: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromLearningActivityPdfUploadRequest(LearningActivityContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: $request->validated('pdf') ? UploadedFile::createFromBase($request->validated('pdf')) : null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            file: null,
            presentation: null,
            quiz: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromLearningActivityAudioUploadRequest(LearningActivityContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: $request->validated('audio') ? UploadedFile::createFromBase($request->validated('audio')) : null,
            word: null,
            powerPoint: null,
            zip: null,
            file: null,
            presentation: null,
            quiz: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromLearningActivityVideoUploadRequest(LearningActivityContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: $request->validated('video') ? UploadedFile::createFromBase($request->validated('video')) : null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            file: null,
            presentation: null,
            quiz: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromLearningActivityWordUploadRequest(LearningActivityContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
            word: $request->validated('word') ? UploadedFile::createFromBase($request->validated('word')) : null,
            powerPoint: null,
            zip: null,
            file: null,
            presentation: null,
            quiz: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromLearningActivityPowerPointUploadRequest(LearningActivityContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: $request->validated('power_point') ? UploadedFile::createFromBase($request->validated('power_point')) : null,
            zip: null,
            file: null,
            presentation: null,
            quiz: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromLearningActivityZipUploadRequest(LearningActivityContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: $request->validated('zip') ? UploadedFile::createFromBase($request->validated('zip')) : null,
            file: null,
            presentation: null,
            quiz: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromInteractiveContentVideoUploadRequest(InteractiveContentContentUploadRequest $request, InteractiveContent $interactiveContent): UploadDto
    {
        if (InteractiveContentType::from($request->validated('content_type')) != $interactiveContent->type)
        {
            throw CustomException::forbidden(ModelName::InteractiveContent, ForbiddenExceptionMessage::InteractiveContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: $request->validated('video') ? UploadedFile::createFromBase($request->validated('video')) : null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            presentation: null,
            quiz: null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromInteractiveContentPresentationUploadRequest(InteractiveContentContentUploadRequest $request, InteractiveContent $interactiveContent): UploadDto
    {
        if (InteractiveContentType::from($request->validated('content_type')) != $interactiveContent->type)
        {
            throw CustomException::forbidden(ModelName::InteractiveContent, ForbiddenExceptionMessage::InteractiveContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            presentation: $request->validated('presentation') ? UploadedFile::createFromBase($request->validated('presentation')) : null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            quiz: null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromInteractiveContentQuizUploadRequest(InteractiveContentContentUploadRequest $request, InteractiveContent $interactiveContent): UploadDto
    {
        if (InteractiveContentType::from($request->validated('content_type')) != $interactiveContent->type)
        {
            throw CustomException::forbidden(ModelName::InteractiveContent, ForbiddenExceptionMessage::InteractiveContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            presentation: null,
            quiz: $request->validated('quiz') ? UploadedFile::createFromBase($request->validated('quiz')) : null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromReusableContentVideoUploadRequest(ReusableContentContentUploadRequest $request, ReusableContent $reusableContent): UploadDto
    {
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: $request->validated('video') ? UploadedFile::createFromBase($request->validated('video')) : null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            presentation: null,
            quiz: null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromReusableContentPresentationUploadRequest(ReusableContentContentUploadRequest $request, ReusableContent $reusableContent): UploadDto
    {
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            presentation: $request->validated('presentation') ? UploadedFile::createFromBase($request->validated('presentation')) : null,
            quiz: null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromReusableContentQuizUploadRequest(ReusableContentContentUploadRequest $request, ReusableContent $reusableContent): UploadDto
    {
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            presentation: null,
            quiz: $request->validated('quiz') ? UploadedFile::createFromBase($request->validated('quiz')) : null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromReusableContentPdfUploadRequest(ReusableContentContentUploadRequest $request, ReusableContent $reusableContent): UploadDto
    {
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: $request->validated('pdf') ? UploadedFile::createFromBase($request->validated('pdf')) : null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            presentation: null,
            quiz: null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromFileUploadRequest(FileUploadRequest $request): UploadDto
    {
        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            presentation: null,
            quiz: null,
            file: $request->validated('file') ? UploadedFile::createFromBase($request->validated('file')) : null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }
}
