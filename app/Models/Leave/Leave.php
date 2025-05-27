<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Leave\LeaveType;
use App\Enums\Leave\LeaveStatus;

class Leave extends Model
{
    protected $fillable = [
        'type',
        'from',
        'to',
        'number_of_days',
        'reason',
        'status',
    ];

    protected $casts = [
        'type' => LeaveType::class,
        'status' => LeaveStatus::class,
    ];
}
