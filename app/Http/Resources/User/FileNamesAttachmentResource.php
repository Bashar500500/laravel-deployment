<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class FileNamesAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url,
            'sizeKb' => $this->size_kb,
            'last_modified' => Carbon::createFromTimestamp($this->updated_at)->format('F j, Y \a\t g:i A'),
        ];
    }
}
