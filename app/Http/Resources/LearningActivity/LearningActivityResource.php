<?php

namespace App\Http\Resources\LearningActivity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sectionId' => $this->section_id,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'flags' => LearningActivityFlagsResource::makeJson($this),
            'content' => LearningActivityContentResource::makeJson($this),
            'thumbnailUrl' => $this->thumbnail_url,
            'completion' => LearningActivityCompletionResource::makeJson($this),
            'availability' => LearningActivityAvailabilityResource::makeJson($this),
            'discussion' => LearningActivityDiscussionResource::makeJson($this),
            'metadata' => LearningActivityMetadataResource::makeJson($this),
        ];
    }
}
