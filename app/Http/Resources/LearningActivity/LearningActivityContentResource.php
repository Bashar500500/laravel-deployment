<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\LearningActivity\LearningActivityType;
use App\Models\InteractiveContent\InteractiveContent;
use App\Models\ReusableContent\ReusableContent;

class LearningActivityContentResource extends JsonResource
{
    public static function makeJson(
        LearningActivityResource $learningActivityResource,
    ): array
    {
        match ($learningActivityResource->type) {
            LearningActivityType::Pdf =>
                $data = LearningActivityContentResource::pdfType($learningActivityResource),
            LearningActivityType::Video =>
                $data = LearningActivityContentResource::videoType($learningActivityResource),
            LearningActivityType::Audio =>
                $data = LearningActivityContentResource::audioType($learningActivityResource),
            LearningActivityType::Word =>
                $data = LearningActivityContentResource::wordType($learningActivityResource),
            LearningActivityType::PowerPoint =>
                $data = LearningActivityContentResource::powerPointType($learningActivityResource),
            LearningActivityType::Zip =>
                $data = LearningActivityContentResource::zipType($learningActivityResource),
            LearningActivityType::InteractiveContent =>
                $data = LearningActivityContentResource::interactiveContentType($learningActivityResource),
            LearningActivityType::ReusableContent =>
                $data = LearningActivityContentResource::reusableContentType($learningActivityResource),
            LearningActivityType::LiveSession =>
                $data = LearningActivityContentResource::liveSessionType($learningActivityResource),
        };

        return [
            'type' => $learningActivityResource->content_type ?? $data['type'],
            'data' => $learningActivityResource->content_type ? $data :
                $data['interactiveContent'] ?? $data['reusableContent'] ?? $data['captions'],
        ];
    }

    private static function pdfType(LearningActivityResource $learningActivityResource): array
    {
        $data['pdf'] = $learningActivityResource->whenLoaded('attachment') ? $learningActivityResource->whenLoaded('attachment')->url : null;

        return $data;
    }

    private static function videoType(LearningActivityResource $learningActivityResource): array
    {
        $data['video'] = $learningActivityResource->whenLoaded('attachment') ? $learningActivityResource->whenLoaded('attachment')->url : null;

        return $data;
    }

    private static function audioType(LearningActivityResource $learningActivityResource): array
    {
        $data['audio'] = $learningActivityResource->whenLoaded('attachment') ? $learningActivityResource->whenLoaded('attachment')->url : null;

        return $data;
    }

    private static function wordType(LearningActivityResource $learningActivityResource): array
    {
        $data['word'] = $learningActivityResource->whenLoaded('attachment') ? $learningActivityResource->whenLoaded('attachment')->url : null;

        return $data;
    }

    private static function powerPointType(LearningActivityResource $learningActivityResource): array
    {
        $data['powerPoint'] = $learningActivityResource->whenLoaded('attachment') ? $learningActivityResource->whenLoaded('attachment')->url : null;

        return $data;
    }

    private static function zipType(LearningActivityResource $learningActivityResource): array
    {
        $data['zip'] = $learningActivityResource->whenLoaded('attachment') ? $learningActivityResource->whenLoaded('attachment')->url : null;

        return $data;
    }

    private static function interactiveContentType(LearningActivityResource $learningActivityResource): array
    {
        $interactiveContent = InteractiveContent::find($learningActivityResource->content_data['interactiveContentId']);
        $data['interactiveContent'] = $interactiveContent->attachment ? $interactiveContent->attachment->url : null;
        $data['type'] = $interactiveContent->type->value;

        return $data;
    }

    private static function reusableContentType(LearningActivityResource $learningActivityResource): array
    {
        $reusableContent = ReusableContent::find($learningActivityResource->content_data['reusableContentId']);
        $data['reusableContent'] = $reusableContent->attachment ? $reusableContent->attachment->url : null;
        $data['type'] = $reusableContent->type->value;

        return $data;
    }

    private static function liveSessionType(LearningActivityResource $learningActivityResource): array
    {
        $data['captions'] = $learningActivityResource->content_data['captions'];
        $data['type'] = $learningActivityResource->type->value;

        return $data;
    }
}
