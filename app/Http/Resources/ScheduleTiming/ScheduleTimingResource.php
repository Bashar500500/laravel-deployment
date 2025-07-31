<?php

namespace App\Http\Resources\ScheduleTiming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ScheduleTimingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorName' => $this->whenLoaded('instructor')->first_name .
                $this->whenLoaded('instructor')->last_name,
            // 'instructorImage' => $this->whenLoaded('instructor')->profile->attachment->url ? $this->whenLoaded('instructor')->profile->attachment->url : null,
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
        $file = Storage::disk('supabase')->get('Profile/' . $id . '/Images/' . $url);
        $encoded = base64_encode($file);
        $mimeType = Storage::disk('supabase')->mimeType('Profile/' . $id . '/Images/' . $url);
        return 'data:' . $mimeType . ';base64,' . $encoded;
    }
}
