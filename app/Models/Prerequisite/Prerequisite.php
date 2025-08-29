<?php

namespace App\Models\Prerequisite;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Prerequisite\PrerequisiteType;
use App\Enums\Prerequisite\PrerequisiteAppliesTo;
use App\Enums\Prerequisite\PrerequisiteCondition;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Prerequisite extends Model
{
    protected $fillable = [
        'instructor_id',
        'type',
        'prerequisiteable_type',
        'prerequisiteable_id',
        'requiredable_type',
        'requiredable_id',
        'applies_to',
        'condition',
        'allow_override',
    ];

    protected $casts = [
        'type' => PrerequisiteType::class,
        'applies_to' => PrerequisiteAppliesTo::class,
        'condition' => PrerequisiteCondition::class,
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function prerequisiteable(): MorphTo
    {
        return $this->morphTo();
    }

    public function requiredable(): MorphTo
    {
        return $this->morphTo();
    }
}
