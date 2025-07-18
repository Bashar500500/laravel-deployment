<?php

namespace App\Http\Resources\TimeLimit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLimitActiveTimersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assessmentTitle' => $this->whenLoaded('assessment')->title,
            'elapsed' => $this->prepareElapsed($this, $this->whenLoaded('assessment')),
            'remaining' => max(0, $this->prepareRemaining($this->whenLoaded('assessment'))),
            'isPaused' => $this->is_paused == 0 ? 'false' : 'true',
            'isSubmitted' => $this->is_submitted == 0 ? 'false' : 'true',
            'isFinished' => $this->is_submitted == 0 ? 'false' : 'true' || $this->prepareIsFinished($this, $this->whenLoaded('assessment')),
        ];
    }

    private function prepareElapsed(object $timer, object $assessment): int
    {
        $duration_minutes = $assessment->timeLimit->duration_minutes;
        $duration = $duration_minutes * 60;

        if ($timer->is_submitted)
        {
            $elapsed = $duration;
        }

        if ($timer->is_paused)
        {
            $elapsed = $timer->paused_at->diffInSeconds($timer->start_time);
        }

        $elapsed = now()->diffInSeconds($timer->start_time);

        if ($elapsed >= $duration && !$timer->is_submitted)
        {
            $timer->update([
                'is_submitted' => true,
                'submitted_at' => now(),
            ]);
        }

        return abs($elapsed);
    }

    private function prepareRemaining(object $assessment): int
    {
        $duration_minutes = $assessment->timeLimit->duration_minutes;
        $duration = $duration_minutes * 60;
        return $duration;
    }

    private function prepareIsFinished(object $timer, object $assessment): bool
    {
        $duration_minutes = $assessment->timeLimit->duration_minutes;
        $duration = $duration_minutes * 60;

        if ($timer->is_submitted)
        {
            $elapsed = $duration;
        }

        if ($timer->is_paused)
        {
            $elapsed = $timer->paused_at->diffInSeconds($timer->start_time);
        }

        $elapsed = now()->diffInSeconds($timer->start_time);

        return abs($elapsed) >= $duration;
    }
}
