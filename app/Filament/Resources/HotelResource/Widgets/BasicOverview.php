<?php

namespace App\Filament\Resources\HotelResource\Widgets;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BasicOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Hotels', Hotel::count()),
            Stat::make('Total Rooms', Room::count()),
            Stat::make('Total Reservations', Reservation::count()),
            Stat::make('Active Reservations', Reservation::where('status', 'confirmed')->count()),
        ];
    }
}
