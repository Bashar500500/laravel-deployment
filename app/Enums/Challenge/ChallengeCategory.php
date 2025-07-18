<?php

namespace App\Enums\Challenge;

enum ChallengeCategory: string
{
    case LearningStudy = 'Learning & Study';
    case Assessment = 'Assessment';
    case Participation = 'Participation';
    case Collaboration = 'Collaboration';
    case Creative = 'Creative';
}
