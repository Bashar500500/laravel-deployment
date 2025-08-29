<?php

namespace App\Http\Resources\ForumPost;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'studentId' => $this->student_id,
            'courseId' => $this->course_id,
            'parentPostId' => $this->parent_post_id,
            'postType' => $this->post_type,
            'content' => $this->content,
            'likesCount' => $this->likes_count,
            'repliesCount' => $this->replies_count,
            'isHelpful' => $this->is_helpful,
        ];
    }
}
