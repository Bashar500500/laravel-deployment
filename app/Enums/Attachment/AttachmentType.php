<?php

namespace App\Enums\Attachment;

enum AttachmentType: string
{
    case Image = 'image';
    case Pdf = 'pdf';
    case Video = 'video';
    case File = 'file';
    case Link = 'link';
    case Presentation = 'presentation';
    case Quiz = 'quiz';
    case Audio = 'audio';
    case Word = 'word';
    case PowerPoint = 'power_point';
    case Zip = 'zip';
}
