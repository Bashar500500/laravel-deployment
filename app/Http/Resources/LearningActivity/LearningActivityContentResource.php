<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\LearningActivity\LearningActivityType;
use App\Models\InteractiveContent\InteractiveContent;

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
                $data = LearningActivityContentResource::AudioType($learningActivityResource),
            LearningActivityType::InteractiveContent =>
                $data = LearningActivityContentResource::InteractiveContentType($learningActivityResource),
            LearningActivityType::ReusableContent =>
                $data = LearningActivityContentResource::ReusableContentType($learningActivityResource),
            LearningActivityType::LiveSession =>
                $data = LearningActivityContentResource::LiveSessionType($learningActivityResource),
        };

        return [
            'type' => $learningActivityResource->content_type,
            'data' => $data,
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

    private static function interactiveContentType(LearningActivityResource $learningActivityResource): array
    {
        $interactiveContent = InteractiveContent::fine($learningActivityResource->content_data['interactiveContentId']);
        $data['interactiveContent'] = $interactiveContent->load('attachment') ? $interactiveContent->load('attachment')->url : null;

        return $data;
    }

    private static function reusableContentType(LearningActivityResource $learningActivityResource): array
    {
        $reusableContent = InteractiveContent::fine($learningActivityResource->content_data['reusableContentId']);
        $data['reusableContent'] = $reusableContent->load('attachment') ? $reusableContent->load('attachment')->url : null;

        return $data;
    }

    private static function liveSessionType(LearningActivityResource $learningActivityResource): array
    {
        $data['captions'] = $learningActivityResource->content_data['captions'];

        return $data;
    }
}
