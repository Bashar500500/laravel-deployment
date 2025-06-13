<?php

namespace App\Enums\Ticket;

enum TicketStatus: string
{
    case Opened = 'Opened';
    case Closed = 'Closed';
    case Inprogress = 'Inprogress';
}
