<?php

namespace App\Enums\PageView;

enum PageViewPageType: string
{
    case Dashboard = 'Dashboard';
    case Course = 'Course';
    case Lesson = 'Lesson';
    case Assignment = 'Assignment';
    case Quiz = 'Quiz';
    case Forum = 'Forum';
    case Profile = 'Profile';
    case Other = 'Other';
}
