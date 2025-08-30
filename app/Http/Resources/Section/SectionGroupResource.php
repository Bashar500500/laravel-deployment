<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SectionGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->group->id,
            'courseId' => $this->group->course_id,
            'name' => $this->group->name,
            'description' => $this->group->description,
            // 'imageUrl' => $this->whenLoaded('attachment') ? $this->whenLoaded('attachment')->url : null,
            'imageUrl' => $this->group->attachment ?
                $this->prepareAttachmentData($this->group->id, $this->group->attachment->url)
                : null,
            'capacity' => SectionGroupCapacityResource::makeJson($this),
            'instructorId' => $this->group->course->instructor->id,
            'students' => $this->group->students?->select('student_id'),
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('supabase')->get('Group/' . $id . '/Images/' . $url);
        $encoded = base64_encode($file);
        $mimeType = Storage::disk('supabase')->mimeType('Group/' . $id . '/Images/' . $url);
        return 'data:' . $mimeType . ';base64,' . $encoded;
    }
}
