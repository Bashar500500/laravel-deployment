<?php

namespace App\Enums\LearningActivity;

enum LearningActivityContentType: string
{
    case Pdf = 'PDF';
    case Video = 'Video';
    case Scorm = 'SCORM';

    public function getEnumsExceptValue(): array
    {
        return match ($this) {
            self::Pdf => ['Video' , 'SCORM'],
            self::Video => ['PDF' , 'SCORM'],
            self::Scorm => ['PDF' , 'Video'],
        };
    }
}
