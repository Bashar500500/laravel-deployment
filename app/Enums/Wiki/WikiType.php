<?php

namespace App\Enums\Wiki;

enum WikiType: string
{
    case CourseMaterials = 'Course Materials';
    case FAQs = 'FAQs';
    case TechnicalGuides = 'Technical Guides';
    case BestPractices = 'Best Practices';
}
