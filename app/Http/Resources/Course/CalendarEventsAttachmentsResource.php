<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Attachment\AttachmentReferenceField;

class CalendarEventsAttachmentsResource extends JsonResource
{
    public static function makeJson(
        CalendarEventsResource $calendarEventsResource,
    ): array
    {
        return [
            'files' => $calendarEventsResource->attachments?->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)->count() == 0 ?
                null :
                CalendarEventsAttachmentResource::collection($calendarEventsResource->attachments->where('reference_field', AttachmentReferenceField::EventAttachmentsFile)),
            'links' => $calendarEventsResource->attachments?->where('reference_field', AttachmentReferenceField::EventAttachmentsLink)->count() == 0 ?
                null :
                CalendarEventsAttachmentResource::collection($calendarEventsResource->attachments->where('reference_field', AttachmentReferenceField::EventAttachmentsLink)),
        ];
    }
}
