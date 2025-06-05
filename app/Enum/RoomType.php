<?php

namespace App\Enum;

enum RoomType: string
{
    case SINGLE = 'single';
    case DOUBLE = 'double';
    case FAMILY = 'family';

    public function getLabel(): string
    {
        return match ($this) {
            self::SINGLE => 'Single Room',
            self::DOUBLE => 'Double Room',
            self::FAMILY => 'Family Room',
        };
    }
}
