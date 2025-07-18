<?php

namespace App\Enums\Badge;

enum BadgeCategory: string
{
    case Learning = 'Learning';
    case Assessment = 'Assessment';
    case Social = 'Social';
    case Collaboration = 'Collaboration';
    case Leadership = 'Leadership';
}
