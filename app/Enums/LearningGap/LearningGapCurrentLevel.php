<?php

namespace App\Enums\LearningGap;

enum LearningGapCurrentLevel: string
{
    case None = 'None';
    case Basic = 'Basic';
    case Proficient = 'Proficient';
    case Advanced = 'Advanced';
    case Expert = 'Expert';
}
