<?php

namespace App\DataTransferObjects\LearningActivity;

use App\Http\Requests\LearningActivity\LearningActivityRequest;
use Illuminate\Http\UploadedFile;
use App\Enums\LearningActivity\LearningActivityType;

class LearningActivityContentDto
{
    public function __construct(
        public readonly ?UploadedFile $pdf,
        public readonly ?UploadedFile $video,
        public readonly ?LearningActivityContentCaptionsDto $learningActivityContentCaptionsDto,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityContentDto
    {
        $type = LearningActivityType::from($request->validated('type'));
        return match ($type) {
            LearningActivityType::Pdf => LearningActivityContentDto::fromPdfType($request),
            LearningActivityType::Video => LearningActivityContentDto::fromVideoType($request),
        };
    }

    private static function fromPdfType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: $request->validated('content.data.pdf') ?
                UploadedFile::createFromBase($request->validated('content.data.pdf')) :
                null,
            video: null,
            learningActivityContentCaptionsDto: null,
        );
    }

    private static function fromVideoType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: $request->validated('content.data.video') ?
                UploadedFile::createFromBase($request->validated('content.data.video')) :
                null,
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }
}
