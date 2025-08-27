<?php

namespace App\Models\UserCertificate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserCertificate extends Model
{
    protected $fillable = [
        'student_id',
        'certificate_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function certificateable(): MorphTo
    {
        return $this->morphTo();
    }
}
