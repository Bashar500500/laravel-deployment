<?php

namespace App\Enums\Assessment;

enum AssessmentType: string
{
    case Quiz = 'quiz';
    case Exam = 'exam';
}
