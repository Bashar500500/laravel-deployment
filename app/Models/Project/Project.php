<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use App\Models\User\User;
use App\Models\Group\Group;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProjectSubmit\ProjectSubmit;
use App\Models\Rubric\Rubric;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class Project extends Model
{
    protected $fillable = [
        'course_id',
        'rubric_id',
        'leader_id',
        'group_id',
        'name',
        'start_date',
        'end_date',
        'description',
        'points',
        'max_submits',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class, 'rubric_id');
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function projectSubmits(): HasMany
    {
        return $this->hasMany(ProjectSubmit::class, 'project_id');
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
