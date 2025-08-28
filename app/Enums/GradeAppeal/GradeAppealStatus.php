<?php

namespace App\Enums\GradeAppeal;

enum GradeAppealStatus: string
{
    case Pending = 'Pending';
    case UnderReview = 'Under Review';
    case Resolved = 'Resolved';
    case Rejected = 'Rejected';
}
