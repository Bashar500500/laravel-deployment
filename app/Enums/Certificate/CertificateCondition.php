<?php

namespace App\Enums\Certificate;

enum CertificateCondition: string
{
    case CourseCompletion = '100% course completion';
    case CompleteSection = 'complete section';
    case FinalScore = '95% or higher final score';
    case SessionAttendance = '100% session attendance';
}
