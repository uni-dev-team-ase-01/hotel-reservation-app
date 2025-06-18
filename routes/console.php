<?php

use App\Console\Commands\CancelNoPaymentMethodReservationsCommand;
use App\Console\Commands\NoShowReservationsCommand;
use App\Console\Commands\ReportsCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(CancelNoPaymentMethodReservationsCommand::class)->dailyAt('19:00');
// Schedule::command(CancelNoPaymentMethodReservationsCommand::class)->everyFiveSeconds();

Schedule::command(NoShowReservationsCommand::class)->dailyAt('19:01');
// Schedule::command(NoShowReservationsCommand::class)->everyFiveSeconds();

Schedule::command(ReportsCommand::class)->dailyAt('19:03');
// Schedule::command(ReportsCommand::class)->everyFiveSeconds();