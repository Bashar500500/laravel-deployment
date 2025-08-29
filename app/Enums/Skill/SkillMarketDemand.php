<?php

namespace App\Enums\Skill;

enum SkillMarketDemand: string
{
    case Low = 'Low';
    case Medium = 'Medium';
    case High = 'High';
    case Critical = 'Critical';
}
