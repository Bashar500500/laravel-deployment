<?php

namespace App\Enums\Challenge;

enum ChallengeType: string
{
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Milestone = 'milestone';
    case Competition = 'competition';
}
