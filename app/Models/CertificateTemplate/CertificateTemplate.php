<?php

namespace App\Models\CertificateTemplate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Certificate\Certificate;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'certificate_template_id');
    }
}
