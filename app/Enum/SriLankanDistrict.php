<?php

namespace App\Enum;

// Option 1: Enum (PHP 8.1+)
enum SriLankanDistrict: string
{
    // Western Province
    case COLOMBO = 'Colombo';
    case GAMPAHA = 'Gampaha';
    case KALUTARA = 'Kalutara';

        // Central Province
    case KANDY = 'Kandy';
    case MATALE = 'Matale';
    case NUWARA_ELIYA = 'Nuwara Eliya';

        // Southern Province
    case GALLE = 'Galle';
    case MATARA = 'Matara';
    case HAMBANTOTA = 'Hambantota';

        // Northern Province
    case JAFFNA = 'Jaffna';
    case KILINOCHCHI = 'Kilinochchi';
    case MANNAR = 'Mannar';
    case MULLAITIVU = 'Mullaitivu';
    case VAVUNIYA = 'Vavuniya';

        // Eastern Province
    case BATTICALOA = 'Batticaloa';
    case AMPARA = 'Ampara';
    case TRINCOMALEE = 'Trincomalee';

        // North Western Province
    case KURUNEGALA = 'Kurunegala';
    case PUTTALAM = 'Puttalam';

        // North Central Province
    case ANURADHAPURA = 'Anuradhapura';
    case POLONNARUWA = 'Polonnaruwa';

        // Uva Province
    case BADULLA = 'Badulla';
    case MONARAGALA = 'Monaragala';

        // Sabaragamuwa Province
    case RATNAPURA = 'Ratnapura';
    case KEGALLE = 'Kegalle';

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->value])
            ->toArray();
    }
}
