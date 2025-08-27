<?php

namespace App\Models\Plagiarism;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Plagiarism\PlagiarismStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\AssignmentSubmit\AssignmentSubmit;

class Plagiarism extends Model
{
    protected $fillable = [
        'assignment_submit_id',
        'score',
        'status',
    ];

    protected $casts = [
        'status' => PlagiarismStatus::class,
    ];

    public function assignmentSubmit(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmit::class, 'assignment_submit_id');
    }
}
