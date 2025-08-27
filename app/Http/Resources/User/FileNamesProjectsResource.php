<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileNamesProjectsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'files' => $this->load('attachments')->count() == 0 ?
                null :
                FileNamesAttachmentResource::collection($this->load('attachments')),
            'projectSubmits' => FileNamesProjectProjectSubmitsResource::collection($this->load('projectSubmits')),
        ];
    }
}
