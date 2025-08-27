<?php

namespace App\Http\Resources\Challenge;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Rule\RuleResource;
use App\Http\Resources\Badge\BadgeResource;

class ChallengeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'instructorId' => $this->instructor_id,
            'title' => $this->title,
            'type' => $this->type,
            'category' => $this->category,
            'difficulty' => $this->difficulty,
            'status' => $this->status,
            'description' => $this->description,
            'conditions' => $this->conditions,
            'rewards' => $this->rewards,
            'rules' => RuleResource::collection($this->whenLoaded('challengeRules')),
            'badges' => BadgeResource::collection($this->whenLoaded('challengeBadges')),
            'challengeCourses' => ChallengeChallengeCoursesResource::collection($this->whenLoaded('challengeCourses')),
            'stats' => ChallengeStatsResource::makeJson($this),
        ];
    }
}
