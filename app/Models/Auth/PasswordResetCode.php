<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    protected $fillable = [
        'email',
        'code'
    ];
}
