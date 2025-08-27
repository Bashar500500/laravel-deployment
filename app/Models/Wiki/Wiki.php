<?php

namespace App\Models\Wiki;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Wiki\WikiType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use App\Models\WikiComment\WikiComment;
// use App\Models\WikiRating\WikiRating;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Attachment\Attachment;

class Wiki extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'tags',
        'collaborators',
    ];

    protected $casts = [
        'type' => WikiType::class,
        'tags' => 'array',
        'collaborators' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function wikiComments(): HasMany
    // {
    //     return $this->hasMany(WikiComment::class, 'wiki_id');
    // }

    // public function wikiRatings(): HasMany
    // {
    //     return $this->hasMany(WikiRating::class, 'wiki_id');
    // }

    public function collaboratorDetails(): array
    {
        if (empty($this->collaborators)) {
            return [];
        }

        return User::whereIn('id', $this->collaborators)
            ->get(['id', 'first_name', 'last_name'])
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => trim("{$user->first_name} {$user->last_name}"),
                ];
            })
            ->toArray();
    }

    // public function averageRating(): float
    // {
    //     $ratings = $this->wikiRatings;

    //     $total = $ratings->sum('rating');
    //     $count = $ratings->count();

    //     return $count > 0 ? round($total / $count, 2) : 0.0;
    // }

    public function typeCounts(): array
    {
        $wikis = self::all();

        $counts = $wikis->groupBy('type')->map->count();

        $formatted = [];
        foreach (WikiType::cases() as $type) {
            $formatted[$type->value] = $counts[$type->value] ?? 0;
        }

        return $formatted;
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
