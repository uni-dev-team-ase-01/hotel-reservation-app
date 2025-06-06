<?php

namespace App\Filament\Customer\Resources\ReservationResource\Pages;

use App\Filament\Customer\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected static ?string $title = 'My Reservations';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
