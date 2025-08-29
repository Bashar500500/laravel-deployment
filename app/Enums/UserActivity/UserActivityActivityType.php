<?php

namespace App\Enums\UserActivity;

enum UserActivityActivityType: string
{
    case Login = 'active';
    case PageView = 'inactive';
    case AssignmentSubmission = 'inactive';
    case QuizAttempt = 'inactive';
    case DiscussionPost = 'active';
    case VideoWatch = 'inactive';
    case Download = 'inactive';
    case ForumParticipation = 'inactive';
}
