<?php

namespace App\Enums\AssignmentSubmit;

enum AssignmentSubmitStatus: string
{
    case Corrected = 'corrected';
    case Pending = 'pending';
    case NotCorrected = 'not corrected';
}
