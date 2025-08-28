<?php

namespace App\Enums\InteractiveContent;

enum InteractiveContentType: string
{
    case Video = 'Video';
    case Quiz = 'Quiz';
    case Presentation = 'Presentation';

    public function getType(): string
    {
        return match ($this) {
            self::Video => 'Video',
            self::Quiz => 'Quiz',
            self::Presentation => 'Presentation',
        };
    }

    public function getEnumsExceptValue(): array
    {
        return match ($this) {
            self::Video => ['Quiz', 'Presentation'],
            self::Quiz => ['Video', 'Presentation'],
            self::Presentation => ['Video', 'Quiz'],
        };
    }
}
