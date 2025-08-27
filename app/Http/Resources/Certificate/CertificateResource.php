<?php

namespace App\Http\Resources\Certificate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'instructorName' => $this->whenLoaded('instructor')->first_name .
                ' ' . $this->whenLoaded('instructor')->last_name,
            'certificateTemplateId' => $this->certificate_template_id,
            'certificateTemplateName' => $this->whenLoaded('certificateTemplate')->name,
            'certificateTemplateColor' => $this->whenLoaded('certificateTemplate')->color,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'condition' => $this->condition,
            'status' => $this->status,
        ];
    }
}
