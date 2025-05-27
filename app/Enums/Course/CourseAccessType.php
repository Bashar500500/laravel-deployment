<?php

namespace App\Enums\Course;

enum CourseAccessType: string
{
    case Draft = 'Draft';
    case Free = 'Free';
    case Paid = 'Paid';
    case Private = 'Private';
    case ComingSoon = 'Coming soon';
}
