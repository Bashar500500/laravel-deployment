<?php

namespace App\Models\SectionCompletion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Section\Section;
use App\Models\User\User;

class SectionCompletion extends Model
{
    protected $fillable = [
        'section_id',
        'student_id',
        'is_complete',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
