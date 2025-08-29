<?php

namespace App\Http\Resources\Challenge;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeChallengeCoursesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->course?->name,
        ];
    }
}
