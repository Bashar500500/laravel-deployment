<?php

namespace App\Enums\Notification;

enum NotificationTopic: string
{
    case VersionNotification = 'version-notifications';
    case WebsiteNotification = 'website-notifications';

    public function getTopic(): string
    {
        return match ($this) {
            self::WebsiteNotification => 'website-notifications',
            self::VersionNotification => 'version-notifications',
        };
    }
}
