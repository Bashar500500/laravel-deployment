<?php

namespace App\Models\Reply;

use Illuminate\Database\Eloquent\Model;
use App\Models\Message\Message;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    protected $fillable = [
        'user_id',
        'message_id',
        'reply',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
