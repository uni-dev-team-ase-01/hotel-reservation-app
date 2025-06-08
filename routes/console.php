<?php

use App\Console\Commands\CancelNoPaymentMethodReservationsCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command(CancelNoPaymentMethodReservationsCommand::class)->everyFiveSeconds();
Schedule::command(CancelNoPaymentMethodReservationsCommand::class)->dailyAt('19:00');
