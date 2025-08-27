<?php

namespace App\Enums\AssignmentSubmit;

enum AssignmentSubmitLevel: string
{
    case Excellent = 'excellent';
    case Good = 'good';
    case S1 = 's1';
    case S2 = 's2';
    case Bad = 'bad';

    public function getType(): string
    {
        return match ($this) {
            self::Excellent => 'excellent',
            self::Good => 'good',
            self::S1 => 's1',
            self::S2 => 's2',
            self::Bad => 'bad',
        };
    }
}
