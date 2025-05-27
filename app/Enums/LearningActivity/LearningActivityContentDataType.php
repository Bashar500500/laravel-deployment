<?php

namespace App\Enums\LearningActivity;

enum LearningActivityContentDataType: string
{
    case PdfContent = 'PdfContent';
    case VideoContent = 'VideoContent';
    case ScormContent = 'ScormContent';
}
