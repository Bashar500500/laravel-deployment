<?php

namespace App\Models\Blank;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Blank extends Model
{
    protected $fillable = [
        'correct_answers',
        'points',
        'case_sensitive',
        'exact_match',
        'hint',
    ];

    protected $casts = [
        'correct_answers' => 'array',
    ];

    public function blankable(): MorphTo
    {
        return $this->morphTo();
    }
}
