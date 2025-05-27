<?php

namespace App\Enums\TeachingHour;

enum TeachingHourStatus: string
{
    case OnTime = 'On Time';
    case Ahead = 'Ahead';
    case Behind = 'Behind';
}
