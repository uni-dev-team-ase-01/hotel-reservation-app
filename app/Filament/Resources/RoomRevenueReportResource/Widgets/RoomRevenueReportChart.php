<?php

namespace App\Filament\Resources\RoomRevenueReportResource\Widgets;

use App\Enum\UserRoleType;
use App\Models\RoomRevenueReport;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RoomRevenueReportChart extends ChartWidget
{
    protected static ?string $heading = 'Room Revenue Chart';

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole([UserRoleType::HOTEL_MANAGER, UserRoleType::HOTEL_CLERK]);
    }

    protected function getData(): array
    {
        $data = DB::table('room_revenue_reports')
            ->selectRaw('room_id, SUM(revenue) as total_revenue, AVG(occupancy) as avg_occupancy')
            ->groupBy('room_id')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Revenue ($)',
                    'data' => $data->pluck('total_revenue')->toArray(),
                    'backgroundColor' => [
                        '#10B981',
                        '#3B82F6',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#EC4899',
                        '#14B8A6',
                        '#F97316',
                    ],
                    'borderColor' => [
                        '#059669',
                        '#2563EB',
                        '#D97706',
                        '#DC2626',
                        '#7C3AED',
                        '#DB2777',
                        '#0F766E',
                        '#EA580C',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('room_id')->map(fn($id) => "Room {$id}")->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue ($)',
                    ],
                ],
            ],
        ];
    }
}
