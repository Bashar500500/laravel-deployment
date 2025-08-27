<?php

namespace App\Enums\Prerequisite;

enum PrerequisiteType: string
{
    case Course = 'Course';
    case Section = 'Section';

    public function getType(): string
    {
        return match ($this) {
            self::Course => 'Course',
            self::Section => 'Section',
        };
    }
}
