<?php

namespace App\Enums\LearningGap;

enum LearningGapPriority: string
{
    case Low = 'Basic';
    case Medium = 'Proficient';
    case High = 'Advanced';
    case Critical = 'Expert';
}
