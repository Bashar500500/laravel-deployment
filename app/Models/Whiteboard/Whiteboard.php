<?php

namespace App\Models\Whiteboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use App\Models\Course\Course;

class Whiteboard extends Model
{
    protected $fillable = [
        'instructor_id',
        'course_id',
        'name',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
