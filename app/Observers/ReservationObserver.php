<?php

namespace App\Observers;

use App\Enum\DiscountRates;
use App\Enum\ReservationStatus;
use App\Enum\UserRoleType;
use App\Mail\ReservationCanceled;
use App\Mail\ReservationCheckedIn;
use App\Mail\ReservationCheckedOut;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationNoShow;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Services\BillService as BillServiceService;
use App\Services\DurationService;
use Carbon\Carbon;
use PHPUnit\Event\Telemetry\Duration;

class ReservationObserver
{
    /**
     * Handle the Reservation "saved" event.
     */
    public function saved(Reservation $reservation): void
    {
        if (!$reservation->user) {
            return;
        }

        if ($reservation->wasChanged('check_in_date') || $reservation->wasChanged('check_out_date')) {
            logger("Recalculate bill due to date changes");
            $this->reCalculateBill($reservation->id);
        }

        if ($reservation->wasChanged('user_id')) {
            $this->reCalculateBill($reservation->id);
        }
        
        if ($reservation->wasChanged('rate_type')){
            $this->reCalculateBill($reservation->id);
        }

        if ($reservation->status === ReservationStatus::CONFIRMED->value) {
            Mail::to($reservation->user)->send(new ReservationConfirmed($reservation));
        }

        if (!$reservation->wasChanged('status')) {
            return;
        }

        if ($reservation->status === ReservationStatus::CANCELLED->value) {
            Mail::to($reservation->user)->send(new ReservationCanceled($reservation));
        }

        if ($reservation->status === ReservationStatus::NO_SHOW->value) {
            if ($reservation->bills()->count() === 0) {
                $reservation->bills()->create([
                    'room_charges' => $reservation->getRoomsPriceAttribute(),
                    'discount' => 0,
                    'service_charges' => 0,
                    'taxes' => 0,
                    'extra_charges' => 0,
                    'total_amount' => $reservation->getRoomsPriceAttribute(),
                    'status' => 'unpaid',
                ]);
            }

            $this->reCalculateBill($reservation->id);

            Mail::to($reservation->user)->send(new ReservationNoShow($reservation));
        }

        if ($reservation->status === ReservationStatus::CHECKED_OUT->value) {
            Mail::to($reservation->user)->send(new ReservationCheckedOut($reservation));
        }

        if ($reservation->status === ReservationStatus::CHECKED_IN->value) {
            Mail::to($reservation->user)->send(new ReservationCheckedIn($reservation));
            if ($reservation->bills()->count() === 0) {
                $reservation->bills()->create([
                    'room_charges' => $reservation->getRoomsPriceAttribute(),
                    'discount' => 0,
                    'service_charges' => 0,
                    'taxes' => 0,
                    'extra_charges' => 0,
                    'total_amount' => $reservation->getRoomsPriceAttribute(),
                    'status' => 'unpaid',
                ]);

                $this->reCalculateBill($reservation->id);
            }
        }
    }

    private function reCalculateBill($reservationId)
    {
        logger()->info('Recalculating bill for reservation ID: ' . $reservationId);
        $billServiceService = new BillServiceService();
        $billServiceService->calculateTotalAmountOfBill($reservationId);
    }
}
