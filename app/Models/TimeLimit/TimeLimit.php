<?php

namespace App\Models\TimeLimit;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TimeLimit\TimeLimitStatus;
use App\Enums\TimeLimit\TimeLimitType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Assessment\Assessment;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Timer\Timer;

class TimeLimit extends Model
{
    protected $fillable = [
        'instructor_id',
        'name',
        'description',
        'status',
        'duration_minutes',
        'type',
        'grace_time_minutes',
        'extension_time_minutes',
        'settings',
        'warnings',
    ];

    protected $casts = [
        'status' => TimeLimitStatus::class,
        'type' => TimeLimitType::class,
        'settings' => 'array',
        'warnings' => 'array',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'time_limit_id');
    }

    public function activeTimers(): Collection
    {
        return Timer::whereHas('assessment', function ($query) {
            $query->where('time_limit_id', $this->id);
        })->where('is_submitted', false)->with('assessment')->get();
    }
}
