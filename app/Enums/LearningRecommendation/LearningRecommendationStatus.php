<?php

namespace App\Enums\LearningRecommendation;

enum LearningRecommendationStatus: string
{
    case Recommended = 'Recommended';
    case Enrolled = 'Enrolled';
    case InProgress = 'InProgress';
    case Completed = 'Completed';
    case Dismissed = 'Dismissed';
}
