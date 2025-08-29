<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Skill\SkillDomain;
use App\Enums\Skill\SkillMarketDemand;

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
}
