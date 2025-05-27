<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
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
            'courses' => ProfileCoursesResource::collection($this->whenLoaded('user')->courses),
            'groups' => ProfileGroupsResource::collection($this->whenLoaded('user')->groups),
        ];
    }

    private function prepareAttachmentData(int $id, string $url): string
    {
        $file = Storage::disk('local')->path('Profile/' . $id . '/Images/' . $url);

        if (!file_exists($file))
        {
            throw CustomException::notFound('Image');
        }

        $data = base64_encode(file_get_contents($file));
        $metadata = mime_content_type($file);

        return 'data:' . $metadata . ';base64,' . $data;
    }
}
