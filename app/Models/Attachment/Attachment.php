<?php

namespace App\Models\Attachment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Enums\Attachment\AttachmentType;
use App\Enums\Attachment\AttachmentReferenceField;

class Attachment extends Model
{
    protected $fillable = [
        'reference_field',
        'type',
        'url',
        'size_kb',
    ];

    protected $casts = [
        'reference_field' => AttachmentReferenceField::class,
        'type' => AttachmentType::class,
    ];

    public function attachmentable(): MorphTo
    {
        return $this->morphTo();
    }
}
