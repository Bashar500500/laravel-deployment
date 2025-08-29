<?php

namespace App\Enums\ProjectSubmit;

enum ProjectSubmitLevel: string
{
    case Excellent = 'excellent';
    case Good = 'good';
    case Satisfactory = 'satisfactory';
    case NeedsImprovement = 'needsImprovement';
    case Unsatisfactory = 'unsatisfactory';

    public function getType(): string
    {
        return match ($this) {
            self::Excellent => 'excellent',
            self::Good => 'good',
            self::Satisfactory => 'satisfactory',
            self::NeedsImprovement => 'needsImprovement',
            self::Unsatisfactory => 'unsatisfactory',
        };
    }
}
