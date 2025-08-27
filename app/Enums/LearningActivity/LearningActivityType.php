<?php

namespace App\Enums\LearningActivity;

enum LearningActivityType: string
{
    case Video = 'Video';
    case Pdf = 'PDF';
    // case Scorm = 'SCORM';
    // case Presentation = 'Presentation';
    // case Audio = 'Audio';
    // case Embedded = 'Embedded';
    // case Assessment = 'Assessment';
    // case Discussion = 'Discussion';
    // case LiveSession = 'LiveSession';
    // case Archived = 'Archived';

    public function getEnumsExceptValue(): array
    {
        return match ($this) {
            self::Pdf => ['Video'],
            self::Video => ['PDF'],
        };
    }
}


/*
content type deleted



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


*/
