<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'courseName' => $this->whenLoaded('course')->name,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            // 'imageUrl' => $this->whenLoaded('attachment') ? $this->whenLoaded('attachment')->url : null,
            'imageUrl' => $this->whenLoaded('attachment') ?
                $this->prepareAttachmentData($this->id, $this->whenLoaded('attachment')->url)
                : null,
            'capacity' => GroupCapacityResource::makeJson($this),
            'instructorId' => $this->whenLoaded('course')->instructor->id,
            'students' => GroupStudentsResource::collection($this->whenLoaded('students')),
            'sectionIds' => GroupSectionsResource::collection($this->whenLoaded('sectionEventGroups')),
            'startDate' => $this->created_at,
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
