<?php

namespace App\Enums\Upload;

enum UploadMessage: string
{
    case Image = 'image';
    case Pdf = 'pdf';
    case Video = 'video';
    case File = 'file';
    case Chunk = 'chunk';
}
