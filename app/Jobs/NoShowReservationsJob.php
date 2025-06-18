<?php

namespace App\Jobs;

use App\Enum\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NoShowReservationsJob implements ShouldQueue
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
        logger()->info('Starting to process no-show reservations...');

        $startOfDayDateTime = \Carbon\Carbon::today()->startOfDay();
        $boundaryDateTime = $startOfDayDateTime->copy()->setTime(19, 0, 0);

        $reservations = Reservation::whereBetween('check_in_date', [$startOfDayDateTime, $boundaryDateTime])
            ->whereIn('status', [ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value])
            // ->whereNull('cancellation_date')
            ->get();
        logger()->info('Found ' . $reservations->count() . ' no-show reservations.');

        foreach ($reservations as $reservation) {
            $reservation->update([
                'status' => ReservationStatus::NO_SHOW->value,
                // 'auto_cancelled' => true,
                // 'cancellation_date' => now(),
                // 'cancellation_reason' => 'No show on check-in date',
            ]);
        }
        logger()->info('Processed ' . $reservations->count() . ' no-show reservations.');
    }
}
