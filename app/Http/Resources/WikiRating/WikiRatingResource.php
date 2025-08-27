<?php

namespace App\Http\Resources\WikiRating;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WikiRatingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'username' => $this->whenLoaded('user')->first_name .
                ' ' . $this->whenLoaded('user')->last_name,
            'rating' => $this->rating,
        ];
    }
}
