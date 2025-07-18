<?php

namespace App\Enums\AssessmentMultipleTypeQuestion;

enum AssessmentMultipleTypeQuestionType: string
{
    case MultipleChoice = 'multiple choice';
    case MultipleAnswers = 'multiple answers';
}
