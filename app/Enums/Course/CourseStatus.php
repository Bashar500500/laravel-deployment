<?php

namespace App\Enums\Course;

enum CourseStatus: string
{
    case Published = 'published';
    case Unpublished = 'unpublished';
}
