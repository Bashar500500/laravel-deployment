<?php

namespace App\Http\Resources\Progress;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseName' => $this->whenLoaded('course')->name,
            'studentName' => $this->whenLoaded('student')->first_name .
                $this->whenLoaded('student')->last_name,
            'progress' => $this->progress,
            'modules' => $this->modules,
            'lastActive' => $this->last_active,
            'streak' => $this->streak,
            'skillLevel' => $this->skill_level,
            'upcomig' => $this->upcomig,
        ];
    }
}
