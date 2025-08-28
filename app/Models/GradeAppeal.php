<?php

namespace App\Models\GradeAppeal;

use Illuminate\Database\Eloquent\Model;
use App\Enums\GradeAppeal\GradeAppealPriority;
use App\Enums\GradeAppeal\GradeAppealStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use App\Models\Assignment\Assignment;

class GradeAppeal extends Model
{
    protected $fillable = [
        'student_id',
        'assignment_id',
        'old_grade',
        'new_grade',
        'reason',
        'submitted',
        'deadline',
        'priority',
        'status',
    ];

    protected $casts = [
        'priority' => GradeAppealPriority::class,
        'status' => GradeAppealStatus::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
