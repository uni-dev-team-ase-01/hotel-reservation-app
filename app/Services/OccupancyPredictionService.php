<?php

namespace App\Services;

use App\Models\DailyRevenueReport;
use App\Models\OccupancyPrediction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OccupancyPredictionService
{
    public function predictNextWeekOccupancy(int $hotelId): Collection
    {
        $predictions = collect();

        /**
         * get from database
         * date
         * occupancy
         * day_of_week
         * month
         * year
         */
        $historicalData = $this->getHistoricalData($hotelId);

        if ($historicalData->isEmpty()) {
            throw new \Exception('Insufficient historical data for prediction');
        }

        $startDate = Carbon::now()->addDay();

        /**
         * Predict occupancy for the next 7 days
         */
        for ($i = 0; $i < 7; $i++) {
            $targetDate = $startDate->copy()->addDays($i);
            $prediction = $this->predictSingleDay($hotelId, $targetDate, $historicalData);
            $predictions->push($prediction);
        }

        return $predictions;
    }

    private function predictSingleDay(int $hotelId, Carbon $date, Collection $historicalData): array
    {
        /**
         * Extract day of week and month from the date
         * to use in prediction calculations
         */
        $dayOfWeek = $date->dayOfWeek;
        $month = $date->month;

        // Method 1: Day-of-week average
        $dayOfWeekData = $historicalData->where('day_of_week', $dayOfWeek);
        $dayOfWeekAvg = $dayOfWeekData->avg('occupancy') ?? 0;

        // Method 2: Seasonal trend
        $monthData = $historicalData->where('month', $month);
        $seasonalAvg = $monthData->avg('occupancy') ?? 0;

        // Method 3: Recent trend (last 4 weeks)
        $recentData = $historicalData->where('date', '>=', $date->copy()->subWeeks(4));
        $recentTrend = $this->calculateTrend($recentData);

        // Method 4: Moving average (last 30 days)
        $movingAvgData = $historicalData->where('date', '>=', $date->copy()->subDays(30));
        $movingAvg = $movingAvgData->avg('occupancy') ?? 0;

        // Weighted prediction
        $weights = [
            'day_of_week' => 0.3,
            'seasonal' => 0.2,
            'trend' => 0.3,
            'moving_avg' => 0.2
        ];

        $prediction = (
            $dayOfWeekAvg * $weights['day_of_week'] +
            $seasonalAvg * $weights['seasonal'] +
            $recentTrend * $weights['trend'] +
            $movingAvg * $weights['moving_avg']
        );

        /**
         * Confidence is calculated based on the variance of the historical data
         * for the specific day of the week and month.
         * A lower variance indicates higher confidence in the prediction.
         */
        $confidence = $this->calculateConfidence($historicalData, $dayOfWeek, $month);

        /**
         * Business rules can include capping the prediction at 100%,
         * applying weekend boosts, or adjusting for holidays.
         */
        $prediction = $this->applyBusinessRules($prediction, $date);

        return [
            'hotel_id' => $hotelId,
            'date' => $date->toDateString(),
            'predicted_occupancy' => round($prediction, 2),
            'confidence_score' => round($confidence, 2),
            'prediction_method' => 'weighted_multi_factor',
            'day_of_week' => $dayOfWeek,
            'components' => [
                'day_of_week_avg' => round($dayOfWeekAvg, 2),
                'seasonal_avg' => round($seasonalAvg, 2),
                'recent_trend' => round($recentTrend, 2),
                'moving_avg' => round($movingAvg, 2)
            ]
        ];
    }

    private function getHistoricalData(int $hotelId): Collection
    {
        return DB::table('daily_revenue_reports')
            ->where('hotel_id', $hotelId)
            ->where('date', '>=', Carbon::now()->subMonths(12))
            ->where('occupancy', '>', 0)
            ->select([
                'date',
                'occupancy',
                DB::raw('DAYOFWEEK(date) as day_of_week'),
                DB::raw('MONTH(date) as month'),
                DB::raw('YEAR(date) as year')
            ])
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);
                return $item;
            });
    }

    private function calculateTrend(Collection $data): float
    {
        if ($data->count() < 2) {
            return $data->avg('occupancy') ?? 0;
        }

        $sortedData = $data->sortBy('date')->values();
        $n = $sortedData->count();

        // Simple linear regression
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        foreach ($sortedData as $index => $item) {
            $x = $index + 1;
            $y = $item->occupancy;

            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        // Project to next point
        return $slope * ($n + 1) + $intercept;
    }

    private function calculateConfidence(Collection $historicalData, int $dayOfWeek, int $month): float
    {
        $dayData = $historicalData->where('day_of_week', $dayOfWeek);
        $monthData = $historicalData->where('month', $month);

        $dayVariance = $this->calculateVariance($dayData->pluck('occupancy'));
        $monthVariance = $this->calculateVariance($monthData->pluck('occupancy'));

        // Lower variance = higher confidence
        $dayConfidence = max(0, 100 - ($dayVariance * 2));
        $monthConfidence = max(0, 100 - ($monthVariance * 2));
        $dataQuality = min(100, ($historicalData->count() / 365) * 100);

        return ($dayConfidence + $monthConfidence + $dataQuality) / 3;
    }

    private function calculateVariance(Collection $values): float
    {
        if ($values->count() < 2) {
            return 0;
        }

        $mean = $values->avg();
        $variance = $values->map(function ($value) use ($mean) {
            return pow($value - $mean, 2);
        })->avg();

        return sqrt($variance);
    }

    private function applyBusinessRules(float $prediction, Carbon $date): float
    {
        // Cap at 100%
        $prediction = min(100, max(0, $prediction));

        // Weekend boost
        if (in_array($date->dayOfWeek, [5, 6])) { // Friday, Saturday
            $prediction *= 1.1;
        }

        // Holiday adjustments (you can expand this)
        if ($this->isHoliday($date)) {
            $prediction *= 1.15;
        }

        return min(100, $prediction);
    }

    private function isHoliday(Carbon $date): bool
    {
        // Add your holiday logic here
        // This is a simple example
        $holidays = [
            '01-01', // New Year
            '12-25', // Christmas
            '02-04', // Independence Day (US)
        ];

        return in_array($date->format('m-d'), $holidays);
    }

    public function savePredictions(Collection $predictions): void
    {
        foreach ($predictions as $prediction) {
            OccupancyPrediction::updateOrCreate(
                [
                    'hotel_id' => $prediction['hotel_id'],
                    'date' => $prediction['date']
                ],
                $prediction
            );
        }
    }
}
