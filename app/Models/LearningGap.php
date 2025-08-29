<?php

namespace App\Models\LearningGap;

use Illuminate\Database\Eloquent\Model;
use App\Enums\LearningGap\LearningGapTargetRole;
use App\Enums\LearningGap\LearningGapCurrentLevel;
use App\Enums\LearningGap\LearningGapRequiredLevel;
use App\Enums\LearningGap\LearningGapGapSize;
use App\Enums\LearningGap\LearningGapPriority;
use App\Enums\LearningGap\LearningGapStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Skill\Skill;

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
        'status' => LearningGapStatus::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
