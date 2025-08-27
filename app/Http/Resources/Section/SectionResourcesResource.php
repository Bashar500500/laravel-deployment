<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class SectionResourcesResource extends JsonResource
{
    public static function makeJson(
        SectionResource $sectionResource,
    ): array
    {
        return [
            'files' => $sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesFile)->count() == 0 ?
                null :
                SectionAttachmentResource::collection($sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesFile)),
            'links' => $sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesLink)->count() == 0 ?
                null :
                SectionAttachmentResource::collection($sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesLink)),
        ];
    }
}
