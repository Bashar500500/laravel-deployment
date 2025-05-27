<?php

namespace App\Enums\Chat;

enum ChatType: string
{
    case Group = 'group';
    case Direct = 'direct';

    public function getType(): string
    {
        return match ($this) {
            self::Group => 'group',
            self::Direct => 'direct',
        };
    }
}
