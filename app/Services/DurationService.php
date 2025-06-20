<?php

namespace App\Services;

use App\Enum\RateType;
use Carbon\Carbon;

class DurationService
{
    public static function getDurationUnitsByRateType($check_in_date, $check_out_date, $rate_type): int
    {
        $nights = round($check_in_date->diffInDays($check_out_date));

        if ($rate_type === RateType::DAILY->value) {
            return $nights;
        }

        if ($rate_type === RateType::WEEKLY->value) {
            return ceil($nights / 7);
        }

        if ($rate_type === RateType::MONTHLY->value) {
            return ceil($nights / 30);
        }

        return $nights;
    }

    public static function getRateTypeByDuration($duration): string
    {
        /*
         * Determine the rate type based on the duration.
         * - Less than 7 days: DAILY
         * - 7 to 29 days: WEEKLY
         * - 30 days or more: MONTHLY
         */
        if ($duration < 7) {
            return RateType::DAILY->value;
        }

        if ($duration < 30) {
            return RateType::WEEKLY->value;
        }

        return RateType::MONTHLY->value;
    }

    public static function getDurationInDays($check_in_date, $check_out_date): int
    {
        // use ceil to ensure that partial days are counted as full days
        // return ceil($check_in_date->diffInDays($check_out_date));

        return round($check_in_date->diffInDays($check_out_date));
    }

    public static function getTotalNights($checkInDate, $checkOutDate): int
    {
        if (! $checkInDate || ! $checkOutDate) {
            return 0;
        }

        return round(Carbon::parse($checkInDate)
            ->diffInDays(Carbon::parse($checkOutDate)));
    }
}
