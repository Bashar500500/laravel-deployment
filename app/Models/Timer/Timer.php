<?php

namespace App\Models\Timer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assessment\Assessment;

class Timer extends Model
{
    protected $fillable = [
        'assessment_id',
        'start_time',
        'elapsed_seconds',
        'is_paused',
        'paused_at',
        'is_submitted',
        'submitted_at',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'paused_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }
}
