<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class FileNamesSectionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'files' => $this->attachments?->where('reference_field', AttachmentReferenceField::SectionResourcesFile)->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->attachments->where('reference_field', AttachmentReferenceField::SectionResourcesFile)),
            'learningActivities' => FileNamesSectionsLearningActivitiesResource::collection(collect($this->learningActivities)),
        ];
    }
}
