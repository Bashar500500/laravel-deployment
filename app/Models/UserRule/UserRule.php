<?php

namespace App\Models\UserRule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use App\Models\ChallengeRuleBadge\ChallengeRuleBadge;

class UserRule extends Model
{
    protected $fillable = [
        'student_id',
        'challenge_rule_badge_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function challengeRuleBadge(): BelongsTo
    {
        return $this->belongsTo(ChallengeRuleBadge::class, 'challenge_rule_badge_id');
    }
}
