<?php

namespace App\Enums\AssignmentSubmit;

enum AssignmentSubmitStatus: string
{
    case Corrected = 'corrected';
    case NotCorrected = 'not corrected';
}
