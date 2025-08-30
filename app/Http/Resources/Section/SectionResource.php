<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LearningActivity\LearningActivityResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->getOrder(),
            'status' => $this->status,
            'access' => SectionAccessResource::makeJson($this),
            'primaryInstructorId' => $this->whenLoaded('course')->instructor->id,
            'groups' => SectionGroupResource::collection($this->whenLoaded('sectionEventGroups')),
            'activities' => LearningActivityResource::collection($this->whenLoaded('learningActivities')->load('attachment')),
            'resources' => $this->whenLoaded('attachments')->count() == 0 ?
                null : SectionResourcesResource::makeJson($this),
            'prerequisites' => SectionPrerequisitesResource::collection($this->whenLoaded('requireds')),
            // 'prerequisites' => $this->whenLoaded('requireds')->count() == 0 ?
            //     null : SectionPrerequisitesResource::makeJson($this),
        ];
    }
}
