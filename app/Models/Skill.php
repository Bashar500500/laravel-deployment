<?php

namespace App\Models\Skill;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Skill\SkillDomain;
use App\Enums\Skill\SkillMarketDemand;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\LearningGap\LearningGap;

class Skill extends Model
{
    protected $fillable = [
        'domain',
        'name',
        'market_demand',
    ];

    protected $casts = [
        'domain' => SkillDomain::class,
        'market_demand' => SkillMarketDemand::class,
    ];

    public function learningGaps(): HasMany
    {
        return $this->hasMany(LearningGap::class, 'student_id');
    }
}
