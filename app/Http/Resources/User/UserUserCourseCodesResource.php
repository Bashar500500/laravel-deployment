<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserUserCourseCodesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'courseId' => $this->course_id,
            'studentCode' => $this->student_code,
        ];
    }
}
