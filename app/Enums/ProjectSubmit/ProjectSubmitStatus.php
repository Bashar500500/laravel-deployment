<?php

namespace App\Enums\ProjectSubmit;

enum ProjectSubmitStatus: string
{
    case Corrected = 'corrected';
    case NotCorrected = 'not corrected';
}
