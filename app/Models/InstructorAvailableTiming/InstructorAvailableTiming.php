<?php

namespace App\Models\InstructorAvailableTiming;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ScheduleTiming\ScheduleTiming;

class InstructorAvailableTiming extends Model
{
    protected $fillable = [
        'schedule_timing_id',
        'date',
        'from',
        'to',
    ];

    public function scheduleTiming(): BelongsTo
    {
        return $this->belongsTo(ScheduleTiming::class, 'schedule_timing_id');
    }
}
