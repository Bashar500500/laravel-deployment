<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileNamesWikisResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->user->first_name .
                $this->user->last_name,
            'title' => $this->title,
            'files' => $this->attachments?->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->attachments),
        ];
    }
}
