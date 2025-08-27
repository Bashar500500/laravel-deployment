<?php

namespace App\Enums\Assignment;

enum AssignmentAllowedFileTypes: string
{
    case Pdf = 'PDF';
    case WordDocument = 'Word Document';
    case WordDocumentDocx = 'Word Document (docx)';
    case TextFile = 'Text File';
    case JPEGImage = 'JPEG Image';
    case PNGImage = 'PNG Image';
    case ZIPArchive = 'ZIP Archive';
    case Blender = 'Blender';
}
