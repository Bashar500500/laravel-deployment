<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\JobRoleSkill\JobRoleSkillJobRole;
use App\Enums\JobRoleSkill\JobRoleSkillRequiredLevel;
use App\Enums\JobRoleSkill\JobRoleSkillImportance;

class JobRoleSkill extends Model
{
    protected $fillable = [
        'skill_id',
        'job_role',
        'required_level',
        'importance',
        'weight',
    ];

    protected $casts = [
        'job_role' => JobRoleSkillJobRole::class,
        'required_level' => JobRoleSkillRequiredLevel::class,
        'importance' => JobRoleSkillImportance::class,
    ];
}
