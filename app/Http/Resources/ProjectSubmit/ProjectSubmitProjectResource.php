<?php

namespace App\Http\Resources\ProjectSubmit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSubmitProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'description' => $this->description,
            'maxSubmits' => $this->max_submits,
        ];
    }
}
