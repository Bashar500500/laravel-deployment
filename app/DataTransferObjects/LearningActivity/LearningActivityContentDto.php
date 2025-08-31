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
        public readonly ?UploadedFile $audio,
        public readonly ?UploadedFile $word,
        public readonly ?UploadedFile $powerPoint,
        public readonly ?UploadedFile $zip,
        public readonly ?int $interactiveContentId,
        public readonly ?int $reusableContentId,
        public readonly ?LearningActivityContentCaptionsDto $learningActivityContentCaptionsDto,
    ) {}

    public static function from(LearningActivityRequest $request): LearningActivityContentDto
    {
        $type = $request->validated('type') ?
            LearningActivityType::from($request->validated('type')) : null;
        return match ($type) {
            LearningActivityType::Pdf => LearningActivityContentDto::fromPdfType($request),
            LearningActivityType::Video => LearningActivityContentDto::fromVideoType($request),
            LearningActivityType::Audio => LearningActivityContentDto::fromAudioType($request),
            LearningActivityType::Word => LearningActivityContentDto::fromWordType($request),
            LearningActivityType::PowerPoint => LearningActivityContentDto::fromPowerPointType($request),
            LearningActivityType::Zip => LearningActivityContentDto::fromZipType($request),
            LearningActivityType::InteractiveContent => LearningActivityContentDto::fromInteractiveContentType($request),
            LearningActivityType::ReusableContent => LearningActivityContentDto::fromReusableContentType($request),
            LearningActivityType::LiveSession => LearningActivityContentDto::fromLiveSessionType($request),
            null => new self(
                pdf: null,
                video: null,
                audio: null,
                word: null,
                powerPoint: null,
                zip: null,
                interactiveContentId: null,
                reusableContentId: null,
                learningActivityContentCaptionsDto: null,
            ),
        };
    }

    private static function fromPdfType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: $request->validated('content.data.pdf') ?
                UploadedFile::createFromBase($request->validated('content.data.pdf')) :
                null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            interactiveContentId: null,
            reusableContentId: null,
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
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            interactiveContentId: null,
            reusableContentId: null,
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }

    private static function fromAudioType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: null,
            audio: $request->validated('content.data.audio') ?
                UploadedFile::createFromBase($request->validated('content.data.audio')) :
                null,
            word: null,
            powerPoint: null,
            zip: null,
            interactiveContentId: null,
            reusableContentId: null,
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }

    private static function fromWordType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: null,
            audio: null,
            word: $request->validated('content.data.word') ?
                UploadedFile::createFromBase($request->validated('content.data.word')) :
                null,
            powerPoint: null,
            zip: null,
            interactiveContentId: null,
            reusableContentId: null,
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }

    private static function fromPowerPointType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: $request->validated('content.data.power_point') ?
                UploadedFile::createFromBase($request->validated('content.data.power_point')) :
                null,
            zip: null,
            interactiveContentId: null,
            reusableContentId: null,
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }

    private static function fromZipType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: $request->validated('content.data.zip') ?
                UploadedFile::createFromBase($request->validated('content.data.zip')) :
                null,
            interactiveContentId: null,
            reusableContentId: null,
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }

    private static function fromInteractiveContentType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            interactiveContentId: $request->validated('content.data.interactive_content_id'),
            reusableContentId: null,
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }

    private static function fromReusableContentType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            interactiveContentId: null,
            reusableContentId: $request->validated('content.data.reusable_content_id'),
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }

    private static function fromLiveSessionType(LearningActivityRequest $request): LearningActivityContentDto
    {
        return new self(
            pdf: null,
            video: null,
            audio: null,
            word: null,
            powerPoint: null,
            zip: null,
            interactiveContentId: null,
            reusableContentId: $request->validated('content.data.reusable_content_id'),
            learningActivityContentCaptionsDto: LearningActivityContentCaptionsDto::from($request),
        );
    }
}
