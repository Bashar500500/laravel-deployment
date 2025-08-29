<?php

namespace App\Models\Section;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\Section\SectionStatus;
use App\Models\Course\Course;
use App\Models\SectionEventGroup\SectionEventGroup;
use App\Models\Group\Group;
use App\Models\LearningActivity\LearningActivity;
use Illuminate\Database\Eloquent\Collection;
use App\Models\SectionCompletion\SectionCompletion;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;
use App\Models\Prerequisite\Prerequisite;

class Section extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'status',
        'access_release_date',
        'access_has_prerequest',
        'access_is_password_protected',
        'access_password',
    ];

    protected $casts = [
        'status' => SectionStatus::class,
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

    public function learningActivities(): HasMany
    {
        return $this->hasMany(LearningActivity::class, 'section_id');
    }

    public function getOrder(): int
    {
        return self::orderBy('created_at')
        ->pluck('id')
        ->search($this->id) + 1;
    }

    public function getPrerequestSectionIds(int $id): Collection
    {
        return self::where('access_has_prerequest', 1)
            ->where('id', '<', $id)
            ->select('id')->get();
    }

    public function sectionCompletions(): HasMany
    {
        return $this->hasMany(SectionCompletion::class, 'section_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function prerequisites(): MorphMany
    {
        return $this->morphMany(Prerequisite::class, 'prerequisiteable');
    }

    public function prerequisite(): MorphOne
    {
        return $this->morphOne(Prerequisite::class, 'prerequisiteable');
    }

    public function requireds(): MorphMany
    {
        return $this->morphMany(Prerequisite::class, 'requiredable');
    }

    public function required(): MorphOne
    {
        return $this->morphOne(Prerequisite::class, 'requiredable');
    }
}
