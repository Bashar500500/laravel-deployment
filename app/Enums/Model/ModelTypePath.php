<?php

namespace App\Enums\Model;

enum ModelTypePath: string
{
    case Chat = 'App\Models\Chat\Chat';
    case User = 'App\Models\User\User';
    case Version = 'App\Models\Version\Version';
    case Website = 'App\Models\Website\Website';

    public function getTypePath(): string
    {
        return match ($this) {
            self::Chat => 'App\Models\Chat\Chat',
            self::User => 'App\Models\User\User',
            self::Version => 'App\Models\Version\Version',
            self::Website => 'App\Models\Website\Website',
        };
    }
}
