<?php

namespace App\Http\Resources\LearningGap;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningGapResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'studentId' => $this->student_id,
            'skillId' => $this->skill_id,
            'targetRole' => $this->target_role,
            'currentLevel' => $this->current_level,
            'requiredLevel' => $this->required_level,
            'gapSize' => $this->gap_size,
            'priority' => $this->priority,
            'gapScore' => $this->gap_score,
            'status' => $this->status,
        ];
    }
}
