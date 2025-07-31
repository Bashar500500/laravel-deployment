<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class InstructorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->first_name . $this->last_name,
            'upcomingSessions' => $this->sections()
                ->where('date', Carbon::today())
                ->count(),
            'profileImage' => $this->profile->attachment ?
                $this->prepareAttachmentData($this->id, $this->profile->attachment->url)
                : null,
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('supabase')->get('Profile/' . $id . '/Images/' . $url);
        $mimeType = Storage::disk('supabase')->mimeType('Profile/' . $id . '/Images/' . $url);
        return 'data:' . $mimeType . ';base64,' . $file;
    }
}
