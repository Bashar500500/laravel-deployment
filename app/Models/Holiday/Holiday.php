<?php

namespace App\Models\Holiday;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Holiday\HolidayDay;

class Holiday extends Model
{
    protected $fillable = [
        'title',
        'date',
        'day',
    ];

    protected $casts = [
        'day' => HolidayDay::class,
    ];
}
