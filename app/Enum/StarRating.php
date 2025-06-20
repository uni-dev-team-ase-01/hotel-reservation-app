<?php

namespace App\Enum;

enum StarRating: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;

    public static function options(): array
    {
        return [
            1 => '1 Star',
            2 => '2 Stars',
            3 => '3 Stars',
            4 => '4 Stars',
            5 => '5 Stars',
        ];
    }
}