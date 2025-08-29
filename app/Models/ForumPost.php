<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ForumPost\ForumPostPostType;

class ForumPost extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'parent_post_id',
        'post_type',
        'content',
        'likes_count',
        'replies_count',
        'is_helpful',
    ];

    protected $casts = [
        'post_type' => ForumPostPostType::class,
    ];
}
