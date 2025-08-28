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
use App\Models\Attachment\Attachment;
use App\Models\GradeAppeal\GradeAppeal;
use App\Models\Rubric\Rubric;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'rubric_id',
        'title',
        'status',
        'description',
        'instructions',
        'due_date',
        'points',
        'peer_review_settings',
        'submission_settings',
        'policies',
    ];

    protected $casts = [
        'status' => AssignmentStatus::class,
        'peer_review_settings' => 'array',
        'submission_settings' => 'array',
        'policies' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class, 'rubric_id');
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

    public function gradeAppeals(): HasMany
    {
        return $this->hasMany(GradeAppeal::class, 'assignment_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }
}
