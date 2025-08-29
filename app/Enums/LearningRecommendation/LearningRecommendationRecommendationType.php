<?php

namespace App\Enums\LearningRecommendation;

enum LearningRecommendationRecommendationType: string
{
    case Course = 'Course';
    case Certification = 'Certification';
    case Project = 'Project';
    case Mentoring = 'Mentoring';
    case Book = 'Book';
    case Workshop = 'Workshop';
    case Conference = 'Conference';
    case OnJobTraining = 'OnJobTraining';
}
