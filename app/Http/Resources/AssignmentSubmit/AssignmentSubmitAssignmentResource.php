<?php

namespace App\Http\Resources\AssignmentSubmit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentSubmitAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'dueDate' => $this->due_date,
            'points' => $this->points,
        ];
    }
}
