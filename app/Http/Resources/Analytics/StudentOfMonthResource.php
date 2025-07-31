<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentOfMonthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => 'x',
            'role' => 'student',
            'description' => 'x',
            'image' => 'x',
            'achievement' => 'x',
        ];
    }
}
