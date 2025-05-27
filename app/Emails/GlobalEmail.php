<?php

namespace App\Emails;

use Illuminate\Support\Facades\Mail;
use App\Models\Email\Email;

class GlobalEmail
{
    public function sendGlobalEmail(Email $email): void
    {
        Mail::raw($email->body, function ($message) use ($email) {
            $message->to($email->user->email)->subject($email->subject);
        });
    }
}
