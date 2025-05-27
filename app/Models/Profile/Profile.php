<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Profile\ProfileGender;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'nationality',
        'phone',
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_phone',
        'permanent_address',
        'temporary_address',
        'enrollment_date',
        'batch',
        'current_semester',
    ];

    protected $casts = [
        'gender' => ProfileGender::class,
        'permanent_address' => 'array',
        'temporary_address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
