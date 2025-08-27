<?php

namespace App\Enums\EnrollmentOption;

enum EnrollmentOptionType: string
{
    case OpenEnrollment = 'Open Enrollment';
    case RestrictedEnrollment = 'Restricted Enrollment';
    case CodeEnrollment = 'Code Enrollment';
}
