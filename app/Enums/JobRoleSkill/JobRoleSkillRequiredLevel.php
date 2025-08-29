<?php

namespace App\Enums\JobRoleSkill;

enum JobRoleSkillRequiredLevel: string
{
    case Basic = 'basic';
    case Proficient = 'proficient';
    case Advanced = 'advanced';
    case Expert = 'expert';
}
