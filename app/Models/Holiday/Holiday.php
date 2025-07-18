<?php

namespace App\Models\Holiday;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Holiday\HolidayDay;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;

class Holiday extends Model
{
    protected $fillable = [
        'instructor_id',
        'title',
        'date',
        'day',
    ];

    protected $casts = [
        'day' => HolidayDay::class,
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
