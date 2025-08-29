<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupStudentsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'studentId' => $this->student_id,
            'studentName' => $this->student->first_name .
                ' ' . $this->student->last_name,
            'studentImage' => $this->student->profile?->load('attachment') ?
                $this->prepareAttachmentData($this->student->profile->id, $this->student->profile->load('attachment')->url) :
                null,
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
