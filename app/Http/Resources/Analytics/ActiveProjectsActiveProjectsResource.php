<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ActiveProjectsActiveProjectsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'description' => $this->description,
            'deadline' => $this->end_date,
            'progress' => $this->prepareProgressData(),
            'course_leader' => $this->prepareCourseLeaderData(),
            'students' => ActiveProjectsActiveProjectsStudentsResource::collection($this->group->students),
        ];
    }

    private function prepareProgressData(): array
    {
        $data['total'] = 0;
        $data['completed'] = 0;
        $data['type'] = 'Lessons';

        return $data;
    }

    private function prepareCourseLeaderData(): array
    {
        $data['name'] = $this->leader->first_name . ' ' . $this->leader->last_name;
        $data['avatar'] = $this->whenLoaded('leader')->profile ?
            ($this->whenLoaded('leader')->profile->attachment->url ?
            $this->prepareLeaderData(
            $this->whenLoaded('leader')->profile->id,
            $this->whenLoaded('leader')->profile->attachment->url,
            ) : null) : null;

        return $data;
    }

    private function prepareLeaderData(int $id, string $url): string
    {
        $file = Storage::disk('supabase')->get('Profile/' . $id . '/Images/' . $url);
        $mimeType = Storage::disk('supabase')->mimeType('Profile/' . $id . '/Images/' . $url);
        return 'data:' . $mimeType . ';base64,' . $file;
    }
}
