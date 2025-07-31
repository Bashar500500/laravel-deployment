<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ActiveProjectsResource extends JsonResource
{
    protected $courseId;

    public function __construct($resource, $courseId)
    {
        parent::__construct($resource);
        $this->courseId = $courseId;
    }

    public function toArray(Request $request): array
    {
        $courseId = $this->courseId;
        return [
            'activeProjects' =>
                ActiveProjectsActiveProjectsResource::collection(
                    $this->courses
                        ->where('id', $courseId)
                        ->first()
                        ->projects
                ),
        ];
    }
}
