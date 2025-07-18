<?php

namespace App\Http\Resources\TimeLimit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLimitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'durationMinutes' => $this->duration_minutes,
            'type' => $this->type,
            'graceTimeMinutes' => $this->grace_time_minutes,
            'extensionTimeMinutes' => $this->extension_time_minutes,
            'settings' => $this->settings,
            'warnings' => $this->warnings,
            'activeTimers' => TimeLimitActiveTimersResource::collection($this->activeTimers()),
        ];
    }
}
