<?php

namespace App\Enums\Assignment;

enum AssignmentType: string
{
    case FileUpload = 'File Upload';
    case TextEntry = 'Text Entry';
    case NoSubmission = 'No Submission';
}
