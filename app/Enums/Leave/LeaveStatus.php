<?php

namespace App\Enums\Leave;

enum LeaveStatus: string
{
    case Approved = 'Approved';
    case Pending = 'Pending';
    case Declined = 'Declined';
}
