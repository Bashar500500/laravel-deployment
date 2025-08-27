<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'userImage' => $this->whenLoaded('attachment') ? $this->whenLoaded('attachment')->url : null,
            'userImage' => $this->whenLoaded('attachment') ?
                $this->prepareAttachmentData($this->id, $this->whenLoaded('attachment')->url)
                : null,
            'dateOfBirth' => $this->date_of_birth,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'phone' => $this->phone,
            'emergencyContactName' => $this->emergency_contact_name,
            'emergencyContactRelation' => $this->emergency_contact_relation,
            'emergencyContactPhone' => $this->emergency_contact_phone,
            'permanentAddress' => $this->permanent_address,
            'temporaryAddress' => $this->temporary_address,
            'enrollmentDate' => $this->enrollment_date,
            'batch' => $this->batch,
            'currentSemester' => $this->current_semester,
            'enrolledCourses' => $this->whenLoaded('user')
                ? $this->whenLoaded('user')->enrolledCourses->map(
                    fn($course) => new ProfileEnrolledCoursesResource($course, $this->user->id)
                )
                : [],
            // 'enrolledCourses' => ProfileEnrolledCoursesResource::collection($this->whenLoaded('user')->enrolledCourses),
            'ownedCourses' => ProfileOwnedCoursesResource::collection($this->whenLoaded('user')->ownedCourses),
            'groups' => ProfileGroupsResource::collection($this->whenLoaded('user')->groups),
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('supabase')->get('Profile/' . $id . '/Images/' . $url);
        $encoded = base64_encode($file);
        $mimeType = Storage::disk('supabase')->mimeType('Profile/' . $id . '/Images/' . $url);
        return 'data:' . $mimeType . ';base64,' . $encoded;
    }
}
