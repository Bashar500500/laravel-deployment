<?php

namespace App\Models\AssignmentSubmit;

use Illuminate\Database\Eloquent\Model;
use App\Enums\AssignmentSubmit\AssignmentSubmitStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assignment\Assignment;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Plagiarism\Plagiarism;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class AssignmentSubmit extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'status',
        'text',
        'score',
        'feedback',
        'detailed_results',
    ];

    protected $casts = [
        'status' => AssignmentSubmitStatus::class,
        'detailed_results' => 'array',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function plagiarism(): HasOne
    {
        return $this->hasOne(Plagiarism::class, 'assignment_submit_id');
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
