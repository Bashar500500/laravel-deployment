<?php

namespace App\Models\SectionGroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Section\Section;
use App\Models\Group\Group;

class SectionGroup extends Model
{
    protected $fillable = [
        'section_id',
        'group_id',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
