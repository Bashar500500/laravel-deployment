<?php

namespace App\Enums\Skill;

enum SkillDomain: string
{
    case Technical = 'Technical';
    case SoftSkill = 'SoftSkill';
    case Leadership = 'Leadership';
    case DomainSpecific = 'DomainSpecific';
}
