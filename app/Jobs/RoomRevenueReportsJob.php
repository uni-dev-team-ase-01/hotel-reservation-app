<?php

namespace App\Jobs;

use App\Enum\ReservationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RoomRevenueReportsJob implements ShouldQueue
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
        $revenueReports = \App\Models\Room::with(['reservations.bills'])
            ->whereHas('reservations', function ($query) {
                $query->whereIn('status', [ReservationStatus::CHECKED_IN->value, ReservationStatus::CHECKED_OUT->value, ReservationStatus::NO_SHOW->value]);
            })
            ->get()
            ->groupBy('hotel_id')
            ->map(function ($hotelRooms, $hotelId) {
                $roomReports = $hotelRooms->groupBy('id')->map(function ($rooms, $roomId) {
                    $room = $rooms->first();

                    $totalRevenue = $room->reservations
                        ->sum(function ($reservation) {
                            return $reservation->bills ? $reservation->bills->sum('total_amount') : 0;
                        });

                    return (object) [
                        'room_id' => $roomId,
                        'hotel_id' => $room->hotel_id,
                        'total_revenue' => $totalRevenue,
                        'occupancy' => $room->reservations
                            ->whereIn('status', [ReservationStatus::CHECKED_IN->value, ReservationStatus::CHECKED_OUT->value, ReservationStatus::NO_SHOW->value])
                            ->sum('number_of_guests'),
                        // 'occupancy' => $room->reservations
                        //     ->whereIn('status', [ReservationStatus::CHECKED_OUT->value, ReservationStatus::NO_SHOW->value])
                        //     ->count(),
                    ];
                });

                return [
                    'hotel_id' => $hotelId,
                    'rooms' => $roomReports,
                    'total_hotel_revenue' => $roomReports->sum('total_revenue'),
                ];
            });

        logger()->info('Number of hotels with revenue: ' . $revenueReports->count());

        foreach ($revenueReports as $hotelData) {
            logger()->info('Hotel ID: ' . $hotelData['hotel_id'], [
                'total_hotel_revenue' => $hotelData['total_hotel_revenue'],
                'rooms_count' => $hotelData['rooms']->count(),
            ]);

            foreach ($hotelData['rooms'] as $roomReport) {
                logger()->info('Room revenue report for room ID: ' . $roomReport->room_id, [
                    'hotel_id' => $roomReport->hotel_id,
                    'total_revenue' => $roomReport->total_revenue,
                    'occupancy' => $roomReport->occupancy,
                ]);

                \App\Models\RoomRevenueReport::updateOrCreate(
                    [
                        'room_id' => $roomReport->room_id,
                        'hotel_id' => $roomReport->hotel_id,
                    ],
                    [
                        'revenue' => $roomReport->total_revenue,
                        'occupancy' => $roomReport->occupancy,
                    ]
                );
            }
        }
    }
}
