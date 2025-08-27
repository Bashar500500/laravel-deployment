<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\LearningActivity\LearningActivity;
use App\Models\User\User;

class Attendance extends Model
{
    protected $fillable = [
        'learning_activity_id',
        'student_id',
        'is_present',
    ];

    public function learningActivity(): BelongsTo
    {
        return $this->belongsTo(LearningActivity::class, 'learning_activity_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
