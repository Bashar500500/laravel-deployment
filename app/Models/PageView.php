<?php

namespace App\Models\PageView;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PageView\PageViewPageType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Course\Course;

class PageView extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'page_url',
        'page_title',
        'page_type',
        'time_on_page',
        'scroll_depth',
        'is_bounce',
    ];

    protected $casts = [
        'page_type' => PageViewPageType::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
