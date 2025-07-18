<?php

namespace App\Enums\Assignment;

enum AssignmentSubmitType: string
{
    case FileUpload = 'File Upload';
    case TextEntry = 'Text Entry';
}
