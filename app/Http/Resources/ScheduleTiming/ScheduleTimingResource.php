<?php

namespace App\Http\Resources\ScheduleTiming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;

class ScheduleTimingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorName' => $this->whenLoaded('instructor')->first_name .
                $this->whenLoaded('instructor')->last_name,
            'instructorImage' => $this->whenLoaded('instructor')->profile->attachment->url ?
                $this->prepareAttachmentData(
                $this->whenLoaded('instructor')->profile->id,
                $this->whenLoaded('instructor')->profile->attachment->url,
                ) : null,
            'course' => $this->whenLoaded('course')->name,
            'instructorAvailableTimings' => $this->instructor_available_timings,
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('local')->path('Profile/' . $id . '/Images/' . $url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        $data = base64_encode(file_get_contents($file));
        $metadata = mime_content_type($file);

        return 'data:' . $metadata . ';base64,' . $data;
    }
}
