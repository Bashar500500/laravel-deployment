<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'is_read',
    ];

    public function notificationable(): MorphTo
    {
        return $this->morphTo();
    }
}
