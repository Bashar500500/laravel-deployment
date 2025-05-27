<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\LearningActivity\LearningActivityContentType;

class LearningActivityContentResource extends JsonResource
{
    public static function makeJson(
        LearningActivityResource $learningActivityResource,
    ): array
    {
        match ($learningActivityResource->content_type) {
            LearningActivityContentType::Pdf =>
                $data = LearningActivityContentResource::pdfType($learningActivityResource),
            LearningActivityContentType::Video =>
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
        $data['sizeMB'] = $learningActivityResource->content_data['sizeMB'];
        $data['pages'] = $learningActivityResource->content_data['pages'];
        $data['watermark'] = $learningActivityResource->content_data['watermark'];

        return $data;
    }

    private static function videoType(LearningActivityResource $learningActivityResource): array
    {
        $data['video'] = $learningActivityResource->whenLoaded('attachment') ? $learningActivityResource->whenLoaded('attachment')->url : null;
        $data['duration'] = $learningActivityResource->content_data['duration'];
        $data['captions'] = $learningActivityResource->content_data['captions'];

        return $data;
    }
}
