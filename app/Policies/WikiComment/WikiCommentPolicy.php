<?php

namespace App\Policies\WikiComment;

use App\Models\WikiComment\WikiComment;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class WikiCommentPolicy
{
    public function update(User $user, WikiComment $wikiComment): bool
    {
        return $wikiComment->user_id == $user->id;
    }

    public function destroy(User $user, WikiComment $wikiComment): bool
    {
        return $wikiComment->user_id == $user->id;
    }
}
