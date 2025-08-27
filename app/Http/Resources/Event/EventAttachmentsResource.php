<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class EventAttachmentsResource extends JsonResource
{
    public static function makeJson(
        EventResource $eventResource,
    ): array
    {
        return [
            'files' => $eventResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->count() == 0 ?
                null :
                EventAttachmentResource::collection($eventResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)),
            'links' => $eventResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::EventAttachmentsLink)->count() == 0 ?
                null :
                EventAttachmentResource::collection($eventResource->whenLoaded('attachments')->where('reference_field', AttachmentReferenceField::EventAttachmentsLink)),
        ];
    }
}
