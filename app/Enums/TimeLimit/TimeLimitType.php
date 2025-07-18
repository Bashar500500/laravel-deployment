<?php

namespace App\Enums\TimeLimit;

enum TimeLimitType: string
{
    case Quiz = 'quiz';
    case Exam = 'exam';
}
