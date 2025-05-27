<?php

namespace App\Services\Global\Email;

use App\Emails\PasswordResetEmail;
use App\Emails\GlobalEmail;
use App\Models\Auth\PasswordResetCode;
use App\Models\Email\Email;

class EmailService
{
    public function __construct(
        protected PasswordResetEmail $passwordResetEmail,
        protected GlobalEmail $globalEmail,
    ) {}

    public function sendPasswordResetCodeEmail(PasswordResetCode $passwordResetCode): void
    {
        $this->passwordResetEmail->sendPasswordResetCodeEmail($passwordResetCode);
    }

    public function sendGlobalEmail(Email $email): void
    {
        $this->globalEmail->sendGlobalEmail($email);
    }
}
