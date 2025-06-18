<?php

namespace App\Jobs;

use App\Enum\ReservationStatus;
use App\Models\DailyRevenueReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DailyRevenueReportsJob implements ShouldQueue
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
        $startOfDayDateTime = \Carbon\Carbon::today()->startOfDay();
        $boundaryDateTime = $startOfDayDateTime->copy()->setTime(19, 0, 0);

        $revenueReports = \App\Models\Reservation::with(['rooms', 'bills'])
            ->whereBetween('check_in_date', [$startOfDayDateTime, $boundaryDateTime])
            ->whereIn('status', [ReservationStatus::CHECKED_IN->value, ReservationStatus::CHECKED_OUT->value, ReservationStatus::NO_SHOW->value])
            ->get()
            ->groupBy('hotel_id')
            ->map(function ($reservations, $hotelId) {
                return (object) [
                    'hotel_id' => $hotelId,
                    'number_of_guests' => $reservations->sum('number_of_guests'),
                    'total_revenue' => $reservations->sum(function ($reservation) {
                        return $reservation->bills ? $reservation->bills->sum('total_amount') : 0;
                    }),
                ];
            });

        logger()->info('Number of reports: ' . $revenueReports->count());

        foreach ($revenueReports as $report) {
            logger()->info('Revenue report for hotel ID: ' . $report->hotel_id, [
                'number_of_guests' => $report->number_of_guests,
                'total_revenue' => $report->total_revenue,
            ]);
            DailyRevenueReport::updateOrCreate(
                [
                    'date' => $startOfDayDateTime->toDateString(),
                    'hotel_id' => $report->hotel_id,
                ],
                [
                    'revenue' => $report->total_revenue,
                    'occupancy' => $report->number_of_guests,
                ]
            );
        }
    }
}
