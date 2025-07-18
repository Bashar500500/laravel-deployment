<?php

namespace App\Enums\TimeLimit;

enum TimeLimitStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
