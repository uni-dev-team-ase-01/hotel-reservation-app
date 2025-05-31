<?php

namespace App\Filament\Resources\TravelCompanyResource\Pages;

use App\Filament\Resources\TravelCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTravelCompany extends EditRecord
{
    protected static string $resource = TravelCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
