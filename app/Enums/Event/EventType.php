<?php

namespace App\Enums\Event;

enum EventType: string
{
    case Lecture = 'Lecture';
    case Deadline = 'Deadline';
    case Reminder = 'Reminder';
    case Meeting = 'Meeting';
}
