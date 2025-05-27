<?php

namespace App\Enums\Notification;

enum NotificationType: string
{
    case User = 'user';
    case Version = 'version';
    case Website = 'website';

    public function getType(): string
    {
        return match ($this) {
            self::User => 'user',
            self::Version => 'version',
            self::Website => 'website',
        };
    }

    public static function getEnum(string $value): self
    {
        return match (true) {
            $value =='User' => self::User,
            $value =='Version' => self::Version,
            $value =='Website' => self::Website,
        };
    }
}
