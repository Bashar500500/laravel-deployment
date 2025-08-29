<?php

namespace App\Enums\ContentEngagement;

enum ContentEngagementContentType: string
{
    case Course = 'Course';
    case Lesson = 'Lesson';
    case Assignment = 'Assignment';
    case Video = 'Video';
    case Document = 'Document';
    case ForumPost = 'ForumPost';
    case Announcement = 'Announcement';
    case Quiz = 'Quiz';
}
