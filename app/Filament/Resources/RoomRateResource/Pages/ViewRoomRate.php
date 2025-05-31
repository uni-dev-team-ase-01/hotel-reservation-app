<?php

namespace App\Filament\Resources\RoomRateResource\Pages;

use App\Filament\Resources\RoomRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoomRate extends ViewRecord
{
    protected static string $resource = RoomRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
