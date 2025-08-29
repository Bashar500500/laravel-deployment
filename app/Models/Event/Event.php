<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Event\EventType;
use App\Enums\Event\EventCategory;
use App\Enums\Event\EventRecurrence;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;
use App\Models\Group\Group;
use App\Models\SectionEventGroup\SectionEventGroup;

class Event extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'type',
        'date',
        'start_time',
        'end_time',
        'category',
        'recurrence',
        'description',
    ];

    protected $casts = [
        'type' => EventType::class,
        'category' => EventCategory::class,
        'recurrence' => EventRecurrence::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function sectionEventGroups(): MorphMany
    {
        return $this->morphMany(SectionEventGroup::class, 'groupable');
    }

    public function sectionEventGroup(): MorphOne
    {
        return $this->morphOne(SectionEventGroup::class, 'groupable');
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
