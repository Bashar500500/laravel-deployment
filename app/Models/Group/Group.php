<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Group\GroupStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Course\Course;
use App\Models\SectionEventGroup\SectionEventGroup;
use App\Models\UserCourseGroup\UserCourseGroup;
use App\Models\Project\Project;
use App\Models\Attachment\Attachment;

class Group extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'status',
        'capacity_min',
        'capacity_max',
        'capacity_current',
    ];

    protected $casts = [
        'status' => GroupStatus::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function sectionEventGroups(): HasMany
    {
        return $this->hasMany(SectionEventGroup::class, 'group_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(UserCourseGroup::class, 'group_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'group_id');
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
