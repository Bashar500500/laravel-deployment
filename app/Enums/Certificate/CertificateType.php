<?php

namespace App\Enums\Certificate;

enum CertificateType: string
{
    case Course = 'Course';
    case Section = 'Section';
    case Achievement = 'Achievement';
    case Participation = 'Participation';
}
