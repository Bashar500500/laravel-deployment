<?php

namespace App\Models\Assignment;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Assignment\AssignmentStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AssignmentSubmit\AssignmentSubmit;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Grade\Grade;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'status',
        'description',
        'instructions',
        'due_date',
        'points',
        'submission_settings',
        'policies',
    ];

    protected $casts = [
        'status' => AssignmentStatus::class,
        'submission_settings' => 'array',
        'policies' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function assignmentSubmits(): HasMany
    {
        return $this->hasMany(AssignmentSubmit::class, 'assignment_id');
    }

    public function grades(): MorphMany
    {
        return $this->morphMany(Grade::class, 'gradeable');
    }

    public function grade(): MorphOne
    {
        return $this->morphOne(Grade::class, 'gradeable');
    }
}
