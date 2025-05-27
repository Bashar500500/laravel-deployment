<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Chat\ChatType;
use App\Models\Message\Message;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Chat extends Model
{
    protected $fillable = [
        'type',
    ];

    protected $casts = [
        'type' => ChatType::class,
    ];

    public function groupChat(): HasOne
    {
        return $this->hasOne(GroupChat::class, 'chat_id');
    }

    public function directChats(): HasMany
    {
        return $this->hasMany(DirectChat::class, 'chat_id');
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function message(): MorphOne
    {
        return $this->morphOne(Message::class, 'messageable');
    }

    public function lastMessage(): MorphOne
    {
        return $this->morphOne(Message::class, 'messageable')->latest('updated_at');
    }

    public function scopeHasDirectChat($query, int $userId)
    {
        return $query->whereHas('directChats', function ($q) use ($userId)
        {
            $q->where('user_id', $userId);
        });
    }
}
