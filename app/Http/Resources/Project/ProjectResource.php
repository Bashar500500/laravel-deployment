<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'description' => $this->description,
            'courseId' => $this->course_id,
            'leader_image' => $this->whenLoaded('leader')->profile ?
                ($this->whenLoaded('leader')->profile->attachment->url ?
                $this->prepareLeaderData(
                $this->whenLoaded('leader')->profile->id,
                $this->whenLoaded('leader')->profile->attachment->url,
                ) : null) : null,
            'group_members_images' => ProjectGroupMembersImagesResource::collection($this->whenLoaded('group')->students),
            'files' => $this->whenLoaded('attachments')->count() == 0 ?
                null :
                ProjectAttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }

    private function prepareLeaderData(int $id, string $url): string
    {
        $file = Storage::disk('local')->path('Profile/' . $id . '/Images/' . $url);
        $data = base64_encode(file_get_contents($file));
        $metadata = mime_content_type($file);
        return 'data:' . $metadata . ';base64,' . $data;
    }
}
