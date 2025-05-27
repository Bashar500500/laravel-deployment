<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reply\Reply;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'is_read',
    ];

    protected $touches = ['messageable'];

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class, 'message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }
}
