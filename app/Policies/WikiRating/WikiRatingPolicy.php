<?php

namespace App\Policies\WikiRating;

use App\Models\WikiRating\WikiRating;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class WikiRatingPolicy
{
    public function update(User $user, WikiRating $wikiRating): bool
    {
        return $wikiRating->user_id == $user->id;
    }

    public function destroy(User $user, WikiRating $wikiRating): bool
    {
        return $wikiRating->user_id == $user->id;
    }
}
