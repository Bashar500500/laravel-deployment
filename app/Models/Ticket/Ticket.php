<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Ticket\TicketPriority;
use App\Enums\Ticket\TicketStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'subject',
        'priority',
        'category',
        'status',
    ];

    protected $casts = [
        'priority' => TicketPriority::class,
        'status' => TicketStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
