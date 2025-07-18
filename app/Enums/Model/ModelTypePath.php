<?php

namespace App\Enums\Model;

enum ModelTypePath: string
{
    case Chat = 'App\Models\Chat\Chat';
    case User = 'App\Models\User\User';
    case Version = 'App\Models\Version\Version';
    case Website = 'App\Models\Website\Website';
    case Rule = 'App\Models\Rule\Rule';
    case Badge = 'App\Models\Badge\Badge';
    case Assessment = 'App\Models\Assessment\Assessment';
    case Assignment = 'App\Models\Assignment\Assignment';

    public function getTypePath(): string
    {
        return match ($this) {
            self::Chat => 'App\Models\Chat\Chat',
            self::User => 'App\Models\User\User',
            self::Version => 'App\Models\Version\Version',
            self::Website => 'App\Models\Website\Website',
            self::Rule => 'App\Models\Rule\Rule',
            self::Badge => 'App\Models\Badge\Badge',
            self::Assessment => 'App\Models\Assessment\Assessment',
            self::Assignment => 'App\Models\Assignment\Assignment',
        };
    }
}
