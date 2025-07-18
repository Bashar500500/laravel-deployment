<?php

namespace App\Models\ChallengeUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Challenge\Challenge;
use App\Models\User\User;

class ChallengeUser extends Model
{
    protected $fillable = [
        'challenge_id',
        'student_id',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
