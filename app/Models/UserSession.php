<?php

namespace App\Models\UserSession;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserSession extends Model
{
    protected $fillable = [
        'session_start',
        'session_end',
        'session_duration',
    ];

    public function sessionable(): MorphTo
    {
        return $this->morphTo();
    }
}
