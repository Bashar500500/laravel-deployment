<?php

namespace App\Enums\Leave;

enum LeaveType: string
{
    case AnnualLeave = 'Annual Leave';
    case MedicalLeave = 'Medical Leave';
    case OtherLeave = 'Other Leave';
}
