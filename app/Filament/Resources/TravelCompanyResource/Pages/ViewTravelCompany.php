<?php

namespace App\Filament\Resources\TravelCompanyResource\Pages;

use App\Filament\Resources\TravelCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTravelCompany extends ViewRecord
{
    protected static string $resource = TravelCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
