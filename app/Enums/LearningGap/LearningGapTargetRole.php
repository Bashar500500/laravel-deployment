<?php

namespace App\Enums\LearningGap;

enum LearningGapTargetRole: string
{
    case Entry = 'Entry';
    case Junior = 'Junior';
    case Mid = 'Mid';
    case Senior = 'Senior';
    case Lead = 'Lead';
    case Principal = 'Principal';
}
