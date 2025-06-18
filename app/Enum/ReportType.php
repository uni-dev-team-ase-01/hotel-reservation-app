<?php

namespace App\Enum;

enum ReportType: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';

    public function getLabel(): string
    {
        return match ($this) {
            self::DAILY => 'Daily Report',
            self::WEEKLY => 'Weekly Report',
            self::MONTHLY => 'Monthly Report',
        };
    }
}
