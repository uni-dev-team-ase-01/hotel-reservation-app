<?php

namespace App\Filament\Resources\OccupancyPredictionResource\Pages;

use App\Filament\Resources\OccupancyPredictionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOccupancyPredictions extends ListRecords
{
    protected static string $resource = OccupancyPredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
