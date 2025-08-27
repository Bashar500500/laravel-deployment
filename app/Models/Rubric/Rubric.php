<?php

namespace App\Models\Rubric;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Rubric\RubricType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RubricCriteria\RubricCriteria;
use App\Models\Assignment\Assignment;
use App\Models\Project\Project;

class Rubric extends Model
{
    protected $fillable = [
        'instructor_id',
        'title',
        'type',
        'description',
    ];

    protected $casts = [
        'type' => RubricType::class,
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function rubricCriterias(): HasMany
    {
        return $this->hasMany(RubricCriteria::class, 'rubric_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'rubric_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'rubric_id');
    }

    public function averageScore(): float
    {
        $submits = $this->assignments
            ->flatMap(fn($assignment) => $assignment->assignmentSubmits);

        $totalScore = $submits->sum('score');
        $totalSubmits = $submits->count();

        return $totalSubmits > 0
            ? round($totalScore / $totalSubmits, 2)
            : 0.0;
    }
}
