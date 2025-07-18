<?php

namespace App\Http\Resources\Badge;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BadgeRecentAwardsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'userId' => $this->student_id,
            'username' => $this->student->first_name . ' ' . $this->student->last_name,
            'course' => $this->challenge->title,
            'awardedAt' => $this->created_at,
        ];
    }
}
