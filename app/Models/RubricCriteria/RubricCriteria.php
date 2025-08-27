<?php

namespace App\Models\RubricCriteria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Rubric\Rubric;

class RubricCriteria extends Model
{
    protected $fillable = [
        'rubric_id',
        'name',
        'weight',
        'description',
        'levels',
    ];

    protected $casts = [
        'levels' => 'array',
    ];

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class, 'rubric_id');
    }
}
