<?php

namespace App\Enums\Prerequisite;

enum PrerequisiteAppliesTo: string
{
    case EntireCourse = 'Entire Course';
    case SpecificSection = 'Specific Section';

    public function getType(): string
    {
        return match ($this) {
            self::EntireCourse => 'Entire Course',
            self::SpecificSection => 'Specific Section',
        };
    }
}
