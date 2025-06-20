<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Enum\UserRoleType;
use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReservation extends ViewRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        $record = $this->record;
        $for = $record->user->hasRole(UserRoleType::TRAVEL_COMPANY) ? 'Travel Company' : 'Customer';
        return "View Reservation for : {$for}";
    }
}
