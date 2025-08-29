<?php

namespace App\Enums\ForumPost;

enum ForumPostPostType: string
{
    case Question = 'Question';
    case Answer = 'Answer';
    case Comment = 'Comment';
    case Discussion = 'Discussion';
}
