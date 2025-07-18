<?php

namespace App\Models\Challenge;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Challenge\ChallengeType;
use App\Enums\Challenge\ChallengeCategory;
use App\Enums\Challenge\ChallengeDifficulty;
use App\Enums\Challenge\ChallengeStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ChallengeRuleBadge\ChallengeRuleBadge;
use App\Models\ChallengeCourse\ChallengeCourse;
use App\Models\UserAward\UserAward;
use App\Models\ChallengeUser\ChallengeUser;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Rule\Rule;
use App\Models\Badge\Badge;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Challenge extends Model
{
    protected $fillable = [
        'instructor_id',
        'title',
        'type',
        'category',
        'difficulty',
        'status',
        'description',
        'conditions',
        'rewards',
    ];

    protected $casts = [
        'type' => ChallengeType::class,
        'category' => ChallengeCategory::class,
        'difficulty' => ChallengeDifficulty::class,
        'status' => ChallengeStatus::class,
        'conditions' => 'array',
        'rewards' => 'array',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function challengeRuleBadges(): HasMany
    {
        return $this->hasMany(ChallengeRuleBadge::class, 'challenge_id');
    }

    public function challengeCourses(): HasMany
    {
        return $this->hasMany(ChallengeCourse::class, 'challenge_id');
    }

    public function awards(): HasMany
    {
        return $this->hasMany(UserAward::class, 'challenge_id');
    }

    public function challengeUsers(): HasMany
    {
        return $this->hasMany(ChallengeUser::class, 'challenge_id');
    }

    public function challengeRules(): HasManyThrough
    {
        return $this->hasManyThrough(Rule::class, ChallengeRuleBadge::class,
            'challenge_id',
            'id',
            'id',
            'contentable_id'
        );
    }

    public function challengeBadges(): HasManyThrough
    {
        return $this->hasManyThrough(Badge::class, ChallengeRuleBadge::class,
            'challenge_id',
            'id',
            'id',
            'contentable_id'
        );
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
