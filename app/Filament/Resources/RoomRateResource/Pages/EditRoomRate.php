<?php

namespace App\Filament\Resources\RoomRateResource\Pages;

use App\Filament\Resources\RoomRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomRate extends EditRecord
{
    protected static string $resource = RoomRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
