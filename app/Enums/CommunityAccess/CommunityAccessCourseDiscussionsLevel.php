<?php

namespace App\Enums\CommunityAccess;

enum CommunityAccessCourseDiscussionsLevel: string
{
    case SeparateForEachActivity = 'Separate for each activity';
    case OneForEntireCourse = 'One for entire course';
}
