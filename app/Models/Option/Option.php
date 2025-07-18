<?php

namespace App\Models\Option;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Option extends Model
{
    protected $fillable = [
        'text',
        'correct',
        'feedback',
    ];

    public function optionable(): MorphTo
    {
        return $this->morphTo();
    }
}
