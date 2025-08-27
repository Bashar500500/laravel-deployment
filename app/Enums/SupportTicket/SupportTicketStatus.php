<?php

namespace App\Enums\SupportTicket;

enum SupportTicketStatus: string
{
    case Opened = 'Opened';
    case Closed = 'Closed';
    case Inprogress = 'Inprogress';
}
