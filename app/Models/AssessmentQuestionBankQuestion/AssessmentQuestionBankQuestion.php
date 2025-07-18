<?php

namespace App\Models\AssessmentQuestionBankQuestion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assessment\Assessment;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AssessmentQuestionBankQuestion extends Model
{
    protected $table = 'questions';
    protected $fillable = [
        'assessment_id',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function questionable(): MorphTo
    {
        return $this->morphTo();
    }
}
