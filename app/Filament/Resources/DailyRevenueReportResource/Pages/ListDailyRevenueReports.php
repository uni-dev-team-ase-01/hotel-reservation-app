<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\DailyRevenueReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyRevenueReports extends ListRecords
{
    protected static string $resource = DailyRevenueReportResource::class;

    protected static ?string $title = 'Daily Revenue Reports';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
