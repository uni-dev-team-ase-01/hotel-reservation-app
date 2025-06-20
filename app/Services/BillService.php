<?php

namespace App\Services;

use App\Enum\DiscountRates;
use App\Enum\UserRoleType;
use App\Models\Reservation;

class BillService
{
    public function applyDiscount(float $total, float $discount): float
    {
        if ($discount < 0 || $discount > 100) {
            throw new \InvalidArgumentException('Discount must be between 0 and 100.');
        }

        return round($total - ($total * ($discount / 100)), 2);
    }

    public function calculateFinalAmount(float $total, float $discount, float $taxes): float
    {
        $totalAfterDiscount = $this->applyDiscount($total, $discount);
        return round($totalAfterDiscount + $taxes, 2);
    }

    public function calculateTotalAmountOfBill($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        if (!$reservation) {
            throw new \Exception('Reservation not found.');
        }

        if ($reservation->bills->isEmpty()) {
            throw new \Exception('No bills found for the reservation.');
        }

        $bill = $reservation->bills->first();

        /*
        * Discount
        */
        $discount = 0;
        if ($reservation->rooms()->count() >= 3) {
            $reservation->user->hasRole(UserRoleType::TRAVEL_COMPANY->value) ?
                $discount = DiscountRates::TRAVEL_COMPANY_DISCOUNT->value :
                $discount = 0;
        }
        logger()->info('Discount : ' . $discount);

        /*
        * Total room charges
        */
        $totalRoomCharges = $reservation->getRoomsPriceAttribute() ?? 0;

        $totalExtraCharges = (int) $bill->extra_charges ?? 0;
        $total = $totalRoomCharges + $totalExtraCharges;
        $discount = (int) $discount ?? 0;
        $taxes = (int) $bill->taxes ?? 0;
        $finalAmount = $this->calculateFinalAmount($total, $discount, $taxes);

        logger()->info('Calculating total amount for reservation', [
            'reservation_id' => $reservationId,
            'total_room_charges' => $totalRoomCharges,
            'total_extra_charges' => $totalExtraCharges,
            'discount' => $discount,
            'taxes' => $taxes,
            'final_amount' => $finalAmount,
        ]);

        $reservation->bills()->first()->update([
            'room_charges' => $totalRoomCharges,
            'extra_charges' => $totalExtraCharges,
            'discount' => $discount,
            'taxes' => $taxes,
            'total_amount' => $finalAmount,
        ]);

        return $finalAmount;
    }

    public function calculateExtraCharges($reservationId): float
    {
        $reservation = Reservation::find($reservationId);
        if (!$reservation) {
            throw new \Exception('Reservation not found.');
        }

        $extraCharges = 0;
        $reservation->bills->first()->billServices->each(function ($billService) use (&$extraCharges) {
            $extraCharges += $billService->charge;
        });

        return round($extraCharges, 2);
    }

    public function setExtraCharges($reservationId, $extraCharges): void
    {
        $reservation = Reservation::find($reservationId);
        if (!$reservation) {
            throw new \Exception('Reservation not found.');
        }

        $bill = $reservation->bills->first();
        if (!$bill) {
            throw new \Exception('Bill not found for the reservation.');
        }

        $bill->update(['extra_charges' => $extraCharges]);
    }
}
