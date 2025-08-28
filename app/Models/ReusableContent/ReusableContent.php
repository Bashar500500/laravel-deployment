<?php

namespace App\Models\ReusableContent;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ReusableContent\ReusableContentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class ReusableContent extends Model
{
    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'type',
        'tags',
        'share_with_community',
    ];

    protected $casts = [
        'type' => ReusableContentType::class,
        'tags' => 'array',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }
}
