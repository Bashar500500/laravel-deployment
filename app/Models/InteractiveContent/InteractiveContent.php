<?php

namespace App\Models\InteractiveContent;

use Illuminate\Database\Eloquent\Model;
use App\Enums\InteractiveContent\InteractiveContentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class InteractiveContent extends Model
{
    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'type',
    ];

    protected $casts = [
        'type' => InteractiveContentType::class,
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
