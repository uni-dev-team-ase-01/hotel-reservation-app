<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case CHECKED_IN = 'checked_in';
    case CHECKED_OUT = 'checked_out';
    case NO_SHOW = 'no_show';

    public function getLabel(): string
    {
        return match ($this) {
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
            self::CHECKED_IN => 'Checked In',
            self::CHECKED_OUT => 'Checked Out',
            self::NO_SHOW => 'No Show',
        };
    }
}
