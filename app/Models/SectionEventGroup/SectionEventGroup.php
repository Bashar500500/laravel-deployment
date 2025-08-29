<?php

namespace App\Models\SectionEventGroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Group\Group;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SectionEventGroup extends Model
{
    protected $fillable = [
        'group_id',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function groupable(): MorphTo
    {
        return $this->morphTo();
    }
}
