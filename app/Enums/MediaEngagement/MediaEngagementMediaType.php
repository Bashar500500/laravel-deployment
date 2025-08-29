<?php

namespace App\Enums\MediaEngagement;

enum MediaEngagementMediaType: string
{
    case Video = 'Video';
    case Audio = 'Audio';
    case Document = 'Document';
    case Presentation = 'Presentation';
    case Animation = 'Animation';
}
