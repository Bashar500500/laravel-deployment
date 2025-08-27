<?php

namespace App\Models\SupportTicket;

use Illuminate\Database\Eloquent\Model;
use App\Enums\SupportTicket\SupportTicketPriority;
use App\Enums\SupportTicket\SupportTicketStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;

class SupportTicket extends Model
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
        'priority' => SupportTicketPriority::class,
        'status' => SupportTicketStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
