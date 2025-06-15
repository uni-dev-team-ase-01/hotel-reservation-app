<?php

namespace App\Observers;

use App\Enum\ReservationStatus;
use App\Mail\ReservationCanceled;
use App\Mail\ReservationCheckedOut;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationNoShow;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

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

        if (!$reservation->wasChanged('status')) {
            return;
        }

        if ($reservation->status === ReservationStatus::CONFIRMED->value) {
            Mail::to($reservation->user)->send(new ReservationConfirmed($reservation));
        }

        if ($reservation->status === ReservationStatus::CANCELLED->value) {
            Mail::to($reservation->user)->send(new ReservationCanceled($reservation));
        }

        if ($reservation->status === ReservationStatus::NO_SHOW->value) {
            Mail::to($reservation->user)->send(new ReservationNoShow($reservation));
        }

        if ($reservation->status === ReservationStatus::CHECKED_OUT->value) {
            Mail::to($reservation->user)->send(new ReservationCheckedOut($reservation));
        }

        if ($reservation->status === ReservationStatus::CHECKED_IN->value) {
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
        }
    }
}
