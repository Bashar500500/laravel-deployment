<?php

namespace App\Models\ChallengeRuleBadge;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Challenge\Challenge;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserRule\UserRule;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChallengeRuleBadge extends Model
{
    protected $fillable = [
        'challenge_id',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }

    public function userRules(): HasMany
    {
        return $this->hasMany(UserRule::class, 'student_id');
    }

    public function contentable(): MorphTo
    {
        return $this->morphTo();
    }
}
