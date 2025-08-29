<?php

namespace App\Models\TeachingHour;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TeachingHour\TeachingHourStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\UserSession\UserSession;

class TeachingHour extends Model
{
    protected $fillable = [
        'instructor_id',
        'total_hours',
        'upcoming',
        'status',
    ];

    protected $casts = [
        'status' => TeachingHourStatus::class,
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function sessions(): MorphMany
    {
        return $this->morphMany(UserSession::class, 'sessionable');
    }

    public function session(): MorphOne
    {
        return $this->morphOne(UserSession::class, 'sessionable');
    }
}
