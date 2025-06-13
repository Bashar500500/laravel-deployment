<?php

namespace App\Http\Resources\CommunityAccess;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommunityAccessResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'community' => CommunityAccessCommunityResource::makeJson($this),
            'course' => CommunityAccessCourseResource::makeJson($this),
        ];
    }
}
