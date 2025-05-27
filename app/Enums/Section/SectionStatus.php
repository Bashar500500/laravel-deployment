<?php

namespace App\Enums\Section;

enum SectionStatus: string
{
    case Draft = 'Draft';
    case Soon = 'Soon';
    case Free = 'Free';
    case Paid = 'Paid';
    case Archived = 'Archived';
}
