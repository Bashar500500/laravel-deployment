<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Leave\LeaveType;
use App\Enums\Leave\LeaveStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;

class Leave extends Model
{
    protected $fillable = [
        'instructor_id',
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

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
