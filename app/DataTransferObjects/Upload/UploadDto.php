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
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->content_type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: $request->validated('pdf') ? UploadedFile::createFromBase($request->validated('pdf')) : null,
            video: null,
            audio: null,
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
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->content_type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: $request->validated('audio') ? UploadedFile::createFromBase($request->validated('audio')) : null,
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
        if (LearningActivityType::from($request->validated('content_type')) != $learningActivity->content_type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: $request->validated('video') ? UploadedFile::createFromBase($request->validated('video')) : null,
            audio: null,
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
        if (InteractiveContentType::from($request->validated('content_type')) != $interactiveContent->content_type)
        {
            throw CustomException::forbidden(ModelName::InteractiveContent, ForbiddenExceptionMessage::InteractiveContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: $request->validated('video') ? UploadedFile::createFromBase($request->validated('video')) : null,
            audio: null,
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
        if (InteractiveContentType::from($request->validated('content_type')) != $interactiveContent->content_type)
        {
            throw CustomException::forbidden(ModelName::InteractiveContent, ForbiddenExceptionMessage::InteractiveContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            presentation: $request->validated('presentation') ? UploadedFile::createFromBase($request->validated('presentation')) : null,
            audio: null,
            quiz: null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromInteractiveContentQuizUploadRequest(InteractiveContentContentUploadRequest $request, InteractiveContent $interactiveContent): UploadDto
    {
        if (InteractiveContentType::from($request->validated('content_type')) != $interactiveContent->content_type)
        {
            throw CustomException::forbidden(ModelName::InteractiveContent, ForbiddenExceptionMessage::InteractiveContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
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
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->content_type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: $request->validated('video') ? UploadedFile::createFromBase($request->validated('video')) : null,
            audio: null,
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
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->content_type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
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
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->content_type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: null,
            video: null,
            audio: null,
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
        if (ReusableContentType::from($request->validated('content_type')) != $reusableContent->content_type)
        {
            throw CustomException::forbidden(ModelName::ReusableContent, ForbiddenExceptionMessage::ReusableContent);
        }

        return new self(
            image: null,
            pdf: $request->validated('pdf') ? UploadedFile::createFromBase($request->validated('pdf')) : null,
            video: null,
            audio: null,
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
            presentation: null,
            quiz: null,
            file: $request->validated('file') ? UploadedFile::createFromBase($request->validated('file')) : null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }
}
