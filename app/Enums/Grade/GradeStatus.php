<?php

namespace App\Enums\Grade;

enum GradeStatus: string
{
    case Submitted = 'Submitted';
    case Late = 'Late';
    case Missing = 'Missing';
}
