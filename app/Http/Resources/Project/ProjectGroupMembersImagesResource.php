<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectGroupMembersImagesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // 'image' => $this->student->profile->load('attachment')->url ? $this->student->profile->load('attachment')->url : null,
            'image' => $this->student->profile->load('attachment')->url ?
                $this->prepareAttachmentData($this->student->profile->id, $this->student->profile->load('attachment')->url)
                : null,
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('local')->path('Profile/' . $id . '/Images/' . $url);
        $data = base64_encode(file_get_contents($file));
        $metadata = mime_content_type($file);
        return 'data:' . $metadata . ';base64,' . $data;
    }
}
