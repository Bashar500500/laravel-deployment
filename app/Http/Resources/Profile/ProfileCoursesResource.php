<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProfileCoursesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_code' => $this->getCourseStudentCode(Auth::id(), $this->id),
            'instructor_id' => $this->instructor_id,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'language' => $this->language,
            'level' => $this->level,
            'timezone' => $this->timezone,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'duration' => $this->duration,
            'price' => $this->price,
        ];
    }
}
