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
            'files' => AttachmentResource::collection($sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesFile)),
            'links' => AttachmentResource::collection($sectionResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::SectionResourcesLink)),
        ];
    }
}
