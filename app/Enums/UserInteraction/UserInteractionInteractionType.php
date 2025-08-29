<?php

namespace App\Enums\UserInteraction;

enum UserInteractionInteractionType: string
{
    case Click = 'Click';
    case Hover = 'Hover';
    case Scroll = 'Scroll';
    case FormSubmit = 'FormSubmit';
    case VideoPlay = 'VideoPlay';
    case VideoPause = 'VideoPause';
    case VideoSeek = 'VideoSeek';
    case Download = 'Download';
    case Share = 'Share';
    case Bookmark = 'Bookmark';
    case Search = 'Search';
    case Filter = 'Filter';
    case Sort = 'Sort';
}
