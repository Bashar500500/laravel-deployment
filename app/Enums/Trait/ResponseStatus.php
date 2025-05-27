<?php

namespace App\Enums\Trait;

enum ResponseStatus: string
{
    case Success = 'success';
    case Error = 'error';

    public function getMessage(): string
    {
        $key = "Trait/statuses.{$this->value}.message";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }
}
