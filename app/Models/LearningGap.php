<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\LearningGap\LearningGapTargetRole;
use App\Enums\LearningGap\LearningGapCurrentLevel;
use App\Enums\LearningGap\LearningGapRequiredLevel;
use App\Enums\LearningGap\LearningGapGapSize;
use App\Enums\LearningGap\LearningGapPriority;

class LearningGap extends Model
{
    protected $fillable = [
        'student_id',
        'skill_id',
        'target_role',
        'current_level',
        'required_level',
        'gap_size',
        'priority',
        'gap_score',
        'status',
    ];

    protected $casts = [
        'target_role' => LearningGapTargetRole::class,
        'current_level' => LearningGapCurrentLevel::class,
        'required_level' => LearningGapRequiredLevel::class,
        'gap_size' => LearningGapGapSize::class,
        'priority' => LearningGapPriority::class,
    ];
}
