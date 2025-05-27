<?php

namespace App\Enums\Auth;

enum PasswordResetEmailData: string
{
    case Subject = 'subject';
    case Body = 'body';

    public function getMessage(): string
    {
        $key = "Email/emails.{$this->value}.message";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }
}
