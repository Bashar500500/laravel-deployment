<?php

namespace App\Enums\LearningGap;

enum LearningGapGapSize: string
{
    case Small = 'Basic';
    case Medium = 'Proficient';
    case Large = 'Advanced';
    case Critical = 'Expert';
}
