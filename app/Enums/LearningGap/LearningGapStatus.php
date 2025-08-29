<?php

namespace App\Enums\LearningGap;

enum LearningGapStatus: string
{
    case Identified = 'Identified';
    case Acknowledged = 'Acknowledged';
    case InProgress = 'InProgress';
    case Completed = 'Completed';
    case Deferred = 'Deferred';
    case Cancelled = 'Cancelled';
}
