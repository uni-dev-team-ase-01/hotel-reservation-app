<?php

namespace App\Filament\Resources\RoomRevenueReportResource\Pages;

use App\Filament\Resources\RoomRevenueReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomRevenueReports extends ListRecords
{
    protected static string $resource = RoomRevenueReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
