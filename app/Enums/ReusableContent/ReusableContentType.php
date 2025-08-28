<?php

namespace App\Enums\ReusableContent;

enum ReusableContentType: string
{
    case Video = 'Video';
    case Quiz = 'Quiz';
    case Presentation = 'Presentation';
    case Pdf = 'Pdf';

    public function getType(): string
    {
        return match ($this) {
            self::Video => 'Video',
            self::Quiz => 'Quiz',
            self::Presentation => 'Presentation',
            self::Pdf => 'Pdf',
        };
    }

    public function getEnumsExceptValue(): array
    {
        return match ($this) {
            self::Video => ['Quiz', 'Presentation', 'Pdf'],
            self::Quiz => ['Video', 'Presentation', 'Pdf'],
            self::Presentation => ['Video', 'Quiz', 'Pdf'],
            self::Pdf => ['Video', 'Quiz', 'Presentation'],
        };
    }
}
