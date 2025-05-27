<?php

namespace App\Enums\Grade;

enum GradeCategory: string
{
    case Exam = 'Exam';
    case Essay = 'Essay';
    case Lab = 'Lab';
    case Quiz = 'Quiz';
}
