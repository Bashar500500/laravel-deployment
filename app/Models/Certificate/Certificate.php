<?php

namespace App\Models\Certificate;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Certificate\CertificateType;
use App\Enums\Certificate\CertificateCondition;
use App\Enums\Certificate\CertificateStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use App\Models\CertificateTemplate\CertificateTemplate;

class Certificate extends Model
{
    protected $fillable = [
        'instructor_id',
        'certificate_template_id',
        'type',
        'name',
        'description',
        'condition',
        'status',
        'settings',
    ];

    protected $casts = [
        'type' => CertificateType::class,
        'condition' => CertificateCondition::class,
        'status' => CertificateStatus::class,
        'settings' => 'array',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function certificateTemplate(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }
}
