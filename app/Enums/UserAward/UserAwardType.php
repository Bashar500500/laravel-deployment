<?php

namespace App\Enums\UserAward;

enum UserAwardType: string
{
    case Point = 'Point';
    case Xp = 'Xp';
    case Challenge = 'Challenge';
    case Rule = 'Rule';
    case Badge = 'Badge';
}
