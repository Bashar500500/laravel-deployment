<?php

namespace App\Models\Version;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Notification\Notification;

class Version extends Model
{
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }

    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notificationable');
    }
}
