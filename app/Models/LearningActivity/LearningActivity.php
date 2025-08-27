<?php

namespace App\Models\LearningActivity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\LearningActivity\LearningActivityType;
use App\Enums\LearningActivity\LearningActivityStatus;
use App\Enums\LearningActivity\LearningActivityMetadataDifficulty;
use App\Enums\LearningActivity\LearningActivityCompletionType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Section\Section;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Attendance\Attendance;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class LearningActivity extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'section_id',
        'type',
        'title',
        'description',
        'status',
        'flags_is_free_preview',
        'flags_is_compulsory',
        'flags_requires_enrollment',
        'content_data',
        'thumbnail_url',
        'completion_type',
        'completion_data',
        'availability_start',
        'availability_end',
        'availability_timezone',
        'discussion_enabled',
        'discussion_moderated',
        'metadata_difficulty',
        'metadata_keywords',
    ];

    protected $casts = [
        'type' => LearningActivityType::class,
        'status' => LearningActivityStatus::class,
        'content_data' => 'array',
        'completion_type' => LearningActivityCompletionType::class,
        'completion_data' => 'array',
        'metadata_difficulty' => LearningActivityMetadataDifficulty::class,
        'metadata_keywords' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'learning_activity_id');
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
