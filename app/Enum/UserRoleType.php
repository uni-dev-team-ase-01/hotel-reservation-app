<?php

namespace App\Enum;

enum UserRoleType: string
{
    case SUPER_ADMIN = 'super-admin';
    case CUSTOMER = 'customer';
    case TRAVEL_COMPANY = 'travel-company';
    case HOTEL_MANAGER = 'hotel-manager';
    case HOTEL_CLERK = 'hotel-clerk';

    public function getLabel(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::CUSTOMER => 'Customer',
            self::TRAVEL_COMPANY => 'Travel Company',
            self::HOTEL_MANAGER => 'Hotel Manager',
            self::HOTEL_CLERK => 'Hotel Clerk',
        };
    }
}
