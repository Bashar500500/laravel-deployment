<?php

namespace App\Enums\LearningActivity;

enum LearningActivityType: string
{
    case Video = 'Video';
    case Pdf = 'PDF';
    case Scorm = 'SCORM';
    case Presentation = 'Presentation';
    case Audio = 'Audio';
    case Embedded = 'Embedded';
    case Assessment = 'Assessment';
    case Discussion = 'Discussion';
    case LiveSession = 'LiveSession';
    case Archived = 'Archived';
}
