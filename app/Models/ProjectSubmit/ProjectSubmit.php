<?php

namespace App\Models\ProjectSubmit;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProjectSubmit\ProjectSubmitStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class ProjectSubmit extends Model
{
    protected $fillable = [
        'project_id',
        'status',
        'score',
        'feedback',
        'detailed_results',
    ];

    protected $casts = [
        'status' => ProjectSubmitStatus::class,
        'detailed_results' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
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
