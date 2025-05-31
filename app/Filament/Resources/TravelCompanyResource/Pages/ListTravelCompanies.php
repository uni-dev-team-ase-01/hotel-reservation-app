<?php

namespace App\Filament\Resources\TravelCompanyResource\Pages;

use App\Filament\Resources\TravelCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTravelCompanies extends ListRecords
{
    protected static string $resource = TravelCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
