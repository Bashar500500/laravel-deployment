<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicPoliciesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => 1,
            'title' => 'x',
            'type' => 'x',
            'description' => 'x',
            'updated_date' => '1-1-1999',
            'downloadable' => false,
            'viewable' => false,
        ];
    }
}
