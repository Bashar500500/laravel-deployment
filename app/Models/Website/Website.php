<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Notification\Notification;

class Website extends Model
{
    protected $fillable = [
        'name',
    ];

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }

    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
