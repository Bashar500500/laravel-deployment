<?php

namespace App\Enums\JobRoleSkill;

enum JobRoleSkillImportance: string
{
    case NiceToHave = 'NiceToHave';
    case Preferred = 'Preferred';
    case Required = 'Required';
    case Critical = 'Critical';
}
