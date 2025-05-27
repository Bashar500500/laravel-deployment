<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionAccessResource extends JsonResource
{
    public static function makeJson(
        SectionResource $sectionResource,
    ): array
    {
        return [
            'releaseDate' => $sectionResource->access_release_date,
            'hasPrerequest' => $sectionResource->access_has_prerequest == 0 ? 'false' : 'true',
            'prerequestSectionIds' => $sectionResource->getPrerequestSectionIds($sectionResource->id),
            'isPasswordProtected' => $sectionResource->access_is_password_protected == 0 ? 'false' : 'true',
            'password' => $sectionResource->access_password,
        ];
    }
}
