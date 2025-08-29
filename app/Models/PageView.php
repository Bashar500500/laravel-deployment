<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PageView\PageViewPageType;

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
}
