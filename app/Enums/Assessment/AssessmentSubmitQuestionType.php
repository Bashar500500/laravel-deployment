<?php

namespace App\Enums\Assessment;

enum AssessmentSubmitQuestionType: string
{
    case FillInBlankQuestion = 'FillInBlankQuestion';
    case MultipleTypeQuestion = 'MultipleTypeQuestion';
    case ShortAnswerQuestion = 'ShortAnswerQuestion';
    case TrueOrFalseQuestion = 'TrueOrFalseQuestion';
    case MultipleTypeAndTrueOrFalse = 'MultipleTypeAndTrueOrFalse';

    public function getType(): string
    {
        return match ($this) {
            self::FillInBlankQuestion => 'FillInBlankQuestion',
            self::MultipleTypeQuestion => 'MultipleTypeQuestion',
            self::ShortAnswerQuestion => 'ShortAnswerQuestion',
            self::TrueOrFalseQuestion => 'TrueOrFalseQuestion',
        };
    }

    public function getEnumsExceptValue(): array
    {
        return match ($this) {
            self::FillInBlankQuestion => ['MultipleTypeQuestion' , 'ShortAnswerQuestion', 'TrueOrFalseQuestion'],
            self::ShortAnswerQuestion => ['FillInBlankQuestion' , 'MultipleTypeQuestion', 'TrueOrFalseQuestion'],
            self::MultipleTypeAndTrueOrFalse => ['FillInBlankQuestion' , 'ShortAnswerQuestion'],
        };
    }

    public function getEnumsWithinValue(): array
    {
        return match ($this) {
            self::MultipleTypeAndTrueOrFalse => ['MultipleTypeQuestion' , 'TrueOrFalseQuestion'],
        };
    }
}
