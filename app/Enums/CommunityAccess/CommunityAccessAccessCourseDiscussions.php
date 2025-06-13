<?php

namespace App\Enums\CommunityAccess;

enum CommunityAccessAccessCourseDiscussions: string
{
    case CoursePlayerAndCommunity = 'Course player and community';
    case OnlyCoursePlayer = 'Only course player';
}
