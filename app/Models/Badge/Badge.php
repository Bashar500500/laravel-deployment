<?php

namespace App\Models\Badge;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Badge\BadgeCategory;
use App\Enums\Badge\BadgeSubCategory;
use App\Enums\Badge\BadgeDifficulty;
use App\Enums\Badge\BadgeStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\ChallengeRuleBadge\ChallengeRuleBadge;
use App\Models\UserAward\UserAward;

class Badge extends Model
{
    protected $fillable = [
        'instructor_id',
        'name',
        'description',
        'category',
        'sub_category',
        'difficulty',
        'icon',
        'color',
        'shape',
        'image_url',
        'status',
        'reward',
    ];

    protected $casts = [
        'category' => BadgeCategory::class,
        'sub_category' => BadgeSubCategory::class,
        'difficulty' => BadgeDifficulty::class,
        'status' => BadgeStatus::class,
        'reward' => 'array',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

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
