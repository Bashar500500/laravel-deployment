<?php

namespace App\DataTransferObjects\Upload;

use App\Http\Requests\Upload\Image\ImageUploadRequest;
use App\Http\Requests\Upload\Content\ContentUploadRequest;
use App\Http\Requests\Upload\File\FileUploadRequest;
use App\Models\LearningActivity\LearningActivity;
use Illuminate\Http\UploadedFile;
use App\Exceptions\CustomException;
use App\Enums\Trait\ModelName;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\LearningActivity\LearningActivityContentType;

class UploadDto
{
    public function __construct(
        public readonly ?UploadedFile $image,
        public readonly ?UploadedFile $pdf,
        public readonly ?UploadedFile $video,
        public readonly ?UploadedFile $file,
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
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromPdfUploadRequest(ContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityContentType::from($request->validated('content_type')) != $learningActivity->content_type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: $request->validated('pdf') ? UploadedFile::createFromBase($request->validated('pdf')) : null,
            video: null,
            file: null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }

    public static function fromVideoUploadRequest(ContentUploadRequest $request, LearningActivity $learningActivity): UploadDto
    {
        if (LearningActivityContentType::from($request->validated('content_type')) != $learningActivity->content_type)
        {
            throw CustomException::forbidden(ModelName::LearningActivity, ForbiddenExceptionMessage::LearningActivity);
        }

        return new self(
            image: null,
            pdf: null,
            video: $request->validated('video') ? UploadedFile::createFromBase($request->validated('video')) : null,
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
            file: $request->validated('file') ? UploadedFile::createFromBase($request->validated('file')) : null,
            dzuuid: $request->validated('dz_uuid'),
            dzChunkIndex: $request->validated('dz_chunk_index'),
            dzTotalChunkCount: $request->validated('dz_total_chunk_count'),
        );
    }
}
