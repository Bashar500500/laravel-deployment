<?php

namespace App\Enums\LearningActivity;

enum LearningActivityCompletionType: string
{
    case View = 'View';
    case Score = 'Score';
    case Composite = 'Composite';

    public function getEnumsExceptValue(): array
    {
        return match ($this) {
            self::View => ['Score' , 'Composite'],
            self::Score => ['View' , 'Composite'],
            self::Composite => ['View' , 'Score'],
        };
    }
}
