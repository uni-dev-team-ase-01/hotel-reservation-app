<?php

namespace App\Enum;

enum RoomType: string
{
    case SINGLE = 'single';
    case DOUBLE = 'double';
    case DELUXE = 'deluxe';

    public function getLabel(): string
    {
        return match ($this) {
            self::SINGLE => 'Single Room',
            self::DOUBLE => 'Double Room',
            self::DELUXE => 'Deluxe Room',
        };
    }
}
