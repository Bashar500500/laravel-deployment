<?php

namespace App\Http\Resources\EnrollmentOption;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'type' => $this->type,
            'period' => $this->period,
            'allowSelfEnrollment' => $this->allow_self_enrollment == 0 ? 'false' : 'true',
            'enableWaitingList' => $this->enable_waiting_list == 0 ? 'false' : 'true',
            'requireInstructorApproval' => $this->require_instructor_approval == 0 ? 'false' : 'true',
            'requirePrerequisites' => $this->require_prerequisites == 0 ? 'false' : 'true',
            'enableNotifications' => $this->enable_notifications == 0 ? 'false' : 'true',
            'enableEmails' => $this->enable_emails == 0 ? 'false' : 'true',
            'groups' => $this->whenLoaded('course')->load('groups')->count() == 0 ?
                null : EnrollmentOptionGroupsResource::makeJson($this),
            'prerequisites' => $this->whenLoaded('course')->load('requireds')->count() == 0 ?
                null : EnrollmentOptionPrerequisitesResource::makeJson($this),
        ];
    }
}
