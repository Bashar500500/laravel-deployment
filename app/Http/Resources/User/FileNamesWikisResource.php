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
            'username' => $this->load('user') ?
                $this->load('user')->first_name . $this->load('user')->last_name :
                null,
            'title' => $this->title,
            'files' => $this->load('attachments')->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->load('attachments')),
        ];
    }
}
