<?php

namespace App\Models\WikiRating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Wiki\Wiki;
use App\Models\User\User;

class WikiRating extends Model
{
    protected $fillable = [
        'wiki_id',
        'user_id',
        'rating',
    ];

    public function wiki(): BelongsTo
    {
        return $this->belongsTo(Wiki::class, 'wiki_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
