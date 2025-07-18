<?php

namespace App\Models\Rule;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Rule\RuleCategory;
use App\Enums\Rule\RuleFrequency;
use App\Enums\Rule\RuleStatus;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\ChallengeRuleBadge\ChallengeRuleBadge;
use App\Models\UserAward\UserAward;

class Rule extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'points',
        'frequency',
        'status',
    ];

    protected $casts = [
        'category' => RuleCategory::class,
        'frequency' => RuleFrequency::class,
        'status' => RuleStatus::class,
    ];

    public function challengeRuleBadges(): MorphMany
    {
        return $this->morphMany(ChallengeRuleBadge::class, 'contentable');
    }

    public function challengeRuleBadge(): MorphOne
    {
        return $this->morphOne(ChallengeRuleBadge::class, 'contentable');
    }

    public function userAwards(): MorphMany
    {
        return $this->morphMany(UserAward::class, 'awardable');
    }

    public function userAward(): MorphOne
    {
        return $this->morphOne(UserAward::class, 'awardable');
    }
}
