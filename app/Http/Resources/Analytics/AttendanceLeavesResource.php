<?php

namespace App\Http\Resources\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AttendanceLeavesResource extends JsonResource
{
    protected $year;

    public function __construct($resource, $year)
    {
        parent::__construct($resource);
        $this->year = $year;
    }

    public function toArray(Request $request): array
    {
        $year = $this->year;
        return [
            'totalLeaves' => $this->leaves
                ->filter(function ($leave) use ($year) {
                    return Carbon::parse($leave->from)->year == $year;
                })->count(),
            'leavesTaken' => 0,
            'leavesAbsent' => 0,
            'pendingApproval' => 0,
            'teachingDays' => 0,
            'quizDays' => 0,
            'year' => $year,
        ];
    }
}
