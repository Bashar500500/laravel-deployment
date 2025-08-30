<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructor_id' => $this->instructor_id,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'language' => $this->language,
            'level' => $this->level,
            'timezone' => $this->timezone,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            // 'cover_image' => $this->whenLoaded('attachment') ? $this->whenLoaded('attachment')->url : null,
            'coverImage' => $this->whenLoaded('attachment') ?
                $this->prepareAttachmentData($this->id, $this->whenLoaded('attachment')->url)
                : null,
            'status' => $this->status,
            'enrollments' => $this->whenLoaded('students')->count(),
            'duration' => $this->duration,
            'price' => $this->price,
            'code' => $this->code,
            'accessSettings' => CourseAccessSettingResource::makeJson($this),
            'features' => CourseFeatureResource::makeJson($this),
            'prerequisites' => CoursePrerequisitesResource::collection($this->whenLoaded('requireds')),
            // 'prerequisites' => $this->whenLoaded('requireds')->count() == 0 ?
            //     null : CoursePrerequisitesResource::collection($this->whenLoaded('requireds')),
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('supabase')->get('Course/' . $id . '/Images/' . $url);
        $encoded = base64_encode($file);
        $mimeType = Storage::disk('supabase')->mimeType('Course/' . $id . '/Images/' . $url);
        return 'data:' . $mimeType . ';base64,' . $encoded;
    }
}
