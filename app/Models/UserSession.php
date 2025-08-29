<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = [
        'session_start',
        'session_end',
        'session_duration',
    ];
}
