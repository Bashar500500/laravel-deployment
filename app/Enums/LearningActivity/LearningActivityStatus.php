<?php

namespace App\Enums\LearningActivity;

enum LearningActivityStatus: string
{
    case Draft = 'Draft';
    case Published = 'Published';
    case Hidden = 'Hidden';
}
