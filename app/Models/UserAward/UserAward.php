<?php

namespace App\Models\UserAward;

use Illuminate\Database\Eloquent\Model;
use App\Enums\UserAward\UserAwardType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use App\Models\Challenge\Challenge;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserAward extends Model
{
    protected $fillable = [
        'student_id',
        'challenge_id',
        'type',
        'number',
    ];

    protected $casts = [
        'type' => UserAwardType::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }

    public function awardable(): MorphTo
    {
        return $this->morphTo();
    }
}
