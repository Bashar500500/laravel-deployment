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
            'accessSettings' => CourseAccessSettingResource::makeJson($this),
            'features' => CourseFeatureResource::makeJson($this),
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('local')->path('Course/' . $id . '/Images/' . $url);
        $data = base64_encode(file_get_contents($file));
        $metadata = mime_content_type($file);
        return 'data:' . $metadata . ';base64,' . $data;
    }
}
