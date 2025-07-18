<?php

namespace App\Enums\Badge;

enum BadgeSubCategory: string
{
    case CourseCompletion = 'Course Completion';
    case ModuleMastery = 'Module Mastery';
    case SkillDevelopment = 'Skill Development';
    case Quizzes = 'Quizzes';
    case Exams = 'Exams';
    case Assignments = 'Assignments';
    case ForumParticipation = 'Forum Participation';
    case PeerHelp = 'Peer Help';
    case CommunityBuilding = 'Community Building';
}
