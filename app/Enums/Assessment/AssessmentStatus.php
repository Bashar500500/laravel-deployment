<?php

namespace App\Enums\Assessment;

enum AssessmentStatus: string
{
    case Published = 'Published';
    case Draft = 'Draft';
}
