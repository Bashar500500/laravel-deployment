<?php

namespace App\Enums\Rubric;

enum RubricType: string
{
    case Assignment = 'Assignment';
    case Project = 'Project';
    case PeerReview = 'PeerReview';
}
