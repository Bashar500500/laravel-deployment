<?php

namespace App\Http\Resources\Email;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'subject' => $this->subject,
            'body' => $this->body,
        ];
    }
}
