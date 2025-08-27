<?php

namespace App\Http\Resources\Wiki;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WikiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'username' => $this->whenLoaded('user')->first_name .
                ' ' . $this->whenLoaded('user')->last_name,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'tags' => $this->tags,
            'collaborators' => $this->collaboratorDetails(),
            // 'comments' => $this->whenLoaded('wikiComments')->count(),
            // 'views' => $this->whenLoaded('wikiRatings')->count(),
            // 'averageRating' => $this->averageRating(),
            'typeCounts' => $this->typeCounts(),
            'files' => $this->whenLoaded('attachments')->count() == 0 ?
                null :
                WikiAttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
