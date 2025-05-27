<?php

namespace App\Emails;

use Illuminate\Support\Facades\Mail;
use App\Models\Auth\PasswordResetCode;
use App\Enums\Auth\PasswordResetEmailData;

class PasswordResetEmail
{
    public function sendPasswordResetCodeEmail(PasswordResetCode $passwordResetCode): void
    {
        Mail::raw(PasswordResetEmailData::Body->getMessage() . $passwordResetCode->code, function ($message) use ($passwordResetCode) {
            $message->to($passwordResetCode->email)->subject(PasswordResetEmailData::Subject->getMessage());
        });
    }
}
