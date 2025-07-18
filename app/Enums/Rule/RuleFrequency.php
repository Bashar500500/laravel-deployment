<?php

namespace App\Enums\Rule;

enum RuleFrequency: string
{
    case PerCourse = 'per course';
    case PerQuiz = 'per quiz';
    case PerPost = 'per post';
}
