<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\LearningActivity\LearningActivityType;

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
        $data['captions'] = $learningActivityResource->content_data['captions'];

        return $data;
    }
}
