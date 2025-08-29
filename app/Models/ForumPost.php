<?php

namespace App\Models\ForumPost;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ForumPost\ForumPostPostType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\models\User\User;
use App\models\Course\Course;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function parentPost(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'parent_post_id');
    }

    public function childPosts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'parent_post_id');
    }
}
