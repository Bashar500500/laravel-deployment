<?php

namespace App\Enums\Attachment;

enum AttachmentType: string
{
    case Image = 'image';
    case Pdf = 'pdf';
    case Video = 'video';
    case File = 'file';
    case Link = 'link';
}
