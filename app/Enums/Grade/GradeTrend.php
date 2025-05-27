<?php

namespace App\Enums\Grade;

enum GradeTrend: string
{
    case Up = 'up';
    case Down = 'down';
    case Neutral = 'neutral';
}
