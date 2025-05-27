<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;
use Illuminate\Http\UploadedFile;
use App\Enums\LearningActivity\LearningActivityContentType;

class LearningActivityContentDto
{
    public function __construct(
        public readonly ?LearningActivityContentType $type,
        public readonly ?UploadedFile $pdf,
        public readonly ?int $sizeMB,
        public readonly ?int $pages,
        public readonly ?bool $watermark,
        public readonly ?UploadedFile $video,
        public readonly ?int $duration,
        public readonly ?LearningActivityContentCaptionsDto $learningActivityContentCaptionsDto,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityContentDto
    {
        $type = LearningActivityContentType::from($request->validated('content.type'));
        return match ($type) {
            LearningActivityContentType::Pdf => LearningActivityContentDto::fromPdfType($request),
            LearningActivityContentType::Video => LearningActivityContentDto::fromVideoType($request),
        };
    }

    private static function fromPdfType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            type: $request->validated('content.type') ?
                LearningActivityContentType::from($request->validated('content.type')) :
                null,
            pdf: $request->validated('content.data.pdf') ?
                UploadedFile::createFromBase($request->validated('content.data.pdf')) :
                null,
            sizeMB: $request->validated('content.data.sizeMB'),
            pages: $request->validated('content.data.pages'),
            watermark: $request->validated('content.data.watermark'),
            video: null,
            duration: null,
            learningActivityContentCaptionsDto: null,
        );
    }

    private static function fromVideoType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            type: $request->validated('content.type') ?
                LearningActivityContentType::from($request->validated('content.type')) :
                null,
            pdf: null,
            sizeMB: null,
            pages: null,
            watermark: null,
            video: $request->validated('content.data.video') ?
                UploadedFile::createFromBase($request->validated('content.data.video')) :
                null,
            duration: $request->validated('content.data.duration'),
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }
}
