<?php

namespace App\Enums\Assignment;

enum AssignmentStatus: string
{
    case Published = 'Published';
    case Draft = 'Draft';
}
