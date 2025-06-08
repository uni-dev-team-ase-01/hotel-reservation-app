<?php

namespace App\Jobs;

use App\Enum\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CancelNoPaymentMethodReservationsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logger()->info('Starting to cancel reservations without payment details...');
        $reservations = Reservation::where(function ($query) {
            $query->whereNull('user_id')
                ->orWhereHas('user', function ($query) {
                    $query->whereNull('stripe_customer_id')
                        ->orWhere('has_stripe_payment_method', false);
                });
        })
            ->whereIn('status', [ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value])
            ->whereNull('cancellation_date')
            ->get();

        logger()->info('Found '.$reservations->count().' reservations without payment details.');

        foreach ($reservations as $reservation) {
            $reservation->update([
                'status' => 'cancelled',
                'auto_cancelled' => true,
                'cancellation_date' => now(),
                'cancellation_reason' => 'No payment details provided',
            ]);
        }

        logger()->info('Cancelled '.$reservations->count().' reservations without payment details.');
    }
}
