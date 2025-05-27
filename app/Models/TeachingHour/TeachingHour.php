<?php

namespace App\Models\TeachingHour;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TeachingHour\TeachingHourStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;

class TeachingHour extends Model
{
    protected $fillable = [
        'instructor_id',
        'total_hours',
        'completed_hours',
        'upcoming',
        'break',
        'status',
    ];

    protected $casts = [
        'status' => TeachingHourStatus::class,
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
