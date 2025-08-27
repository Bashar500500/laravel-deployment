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
            'studentImage' => $this->load('student.profile.attachment') ?
                $this->prepareAttachmentData($this->load('student.profile')->id, $this->load('student.profile.attachment')->url) :
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
