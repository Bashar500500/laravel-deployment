<?php

namespace App\Enums\LearningGap;

enum LearningGapRequiredLevel: string
{
    case Basic = 'Basic';
    case Proficient = 'Proficient';
    case Advanced = 'Advanced';
    case Expert = 'Expert';
}
