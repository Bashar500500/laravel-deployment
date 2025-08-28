<?php

namespace App\Enums\LearningActivity;

enum LearningActivityType: string
{
    case Video = 'Video';
    case Pdf = 'PDF';
    case LiveSession = 'LiveSession';
    case InteractiveContent = 'InteractiveContent';
    case ReusableContent = 'ReusableContent';
    case Audio = 'Audio';
    // case Scorm = 'SCORM';
    // case Presentation = 'Presentation';
    // case Embedded = 'Embedded';
    // case Assessment = 'Assessment';
    // case Discussion = 'Discussion';
    // case Archived = 'Archived';

    public function getEnumsExceptValue(): array
    {
        return match ($this) {
            self::Pdf => ['Video', 'LiveSession', 'InteractiveContent', 'ReusableContent', 'Audio'],
            self::Video => ['Pdf', 'LiveSession', 'InteractiveContent', 'ReusableContent', 'Audio'],
            self::LiveSession => ['Pdf', 'Video', 'InteractiveContent', 'ReusableContent', 'Audio'],
            self::InteractiveContent => ['Pdf', 'Video', 'LiveSession', 'ReusableContent', 'Audio'],
            self::ReusableContent => ['Pdf', 'Video', 'LiveSession', 'InteractiveContent', 'Audio'],
            self::Audio => ['Pdf', 'Video', 'LiveSession', 'InteractiveContent', 'ReusableContent'],
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
