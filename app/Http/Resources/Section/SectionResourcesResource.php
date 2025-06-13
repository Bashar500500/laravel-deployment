<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Attachment\AttachmentResource;
use App\Enums\Attachment\AttachmentReferenceField;

class SectionResourcesResource extends JsonResource
{
    public static function makeJson(
        SectionResource $sectionResource,
    ): array
    {
        return [
            'files' => $sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesFile) ?
                SectionAttachmentResource::collection($sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesFile)) :
                null,
            'links' => $sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesLink) ?
                SectionAttachmentResource::collection($sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesLink)) :
                null,
        ];
    }
}
