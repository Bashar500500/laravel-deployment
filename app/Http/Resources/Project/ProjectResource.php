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
            'maxSubmits' => $this->max_submits,
            'progress' => $this->prepareProgressData($this->whenLoaded('projectSubmits')->count(),
                $this->max_submits),
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
        $file = Storage::disk('supabase')->get('Profile/' . $id . '/Images/' . $url);
        $encoded = base64_encode($file);
        $mimeType = Storage::disk('supabase')->mimeType('Profile/' . $id . '/Images/' . $url);
        return 'data:' . $mimeType . ';base64,' . $encoded;
    }

    private function prepareProgressData(int $count, int $max_submits): float
    {
        return $count == 0 ? 0 : ($count / $max_submits) * 100;
    }
}
