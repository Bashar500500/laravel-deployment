<?php

namespace App\Enums\Event;

enum EventRecurrence: string
{
    case DoesNotRepeat = 'Does not repeat';
    case Daily = 'Daily';
    case WeeklyOnThisDay = 'Weekly on this day';
    case MonthlyOnThisDate = 'Monthly on this date';
}
