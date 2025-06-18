<?php

namespace App\Enums\Event;

enum EventCategory: string
{
    case Lecture = 'Lecture';
    case AssignmentDue = 'Assignment due';
    case Exam = 'Exam';
    case LabSession = 'Lab session';
    case NotAvaiableHours = 'Not available hours';
}
