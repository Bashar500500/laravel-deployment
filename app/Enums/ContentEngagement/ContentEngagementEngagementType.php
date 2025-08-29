<?php

namespace App\Enums\ContentEngagement;

enum ContentEngagementEngagementType: string
{
    case Like = 'Like';
    case Dislike = 'Dislike';
    case Love = 'Love';
    case Comment = 'Comment';
    case Share = 'Share';
    case Bookmark = 'Bookmark';
    case Rating = 'Rating';
    case Reaction = 'Reaction';
}
