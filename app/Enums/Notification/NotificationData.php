<?php

namespace App\Enums\Notification;

enum NotificationData: string
{
    case Message = 'message';
    case Reply = 'reply';

    public function getTitle(): string
    {
        $key = "Notification/notifications.{$this->value}.title";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }

    public function getBody(): string
    {
        $key = "Notification/notifications.{$this->value}.body";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }
}
