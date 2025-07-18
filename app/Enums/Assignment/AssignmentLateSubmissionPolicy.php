<?php

namespace App\Enums\Assignment;

enum AssignmentLateSubmissionPolicy: string
{
    case NoLateSubmissions = 'No Late Submissions';
    case AcceptwithPenalty = 'Accept with Penalty';
    case AcceptUntilDate = 'Accept Until Date';
    case AlwaysAccept = 'Always Accept';
}
