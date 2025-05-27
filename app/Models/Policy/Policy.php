<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
    ];
}
