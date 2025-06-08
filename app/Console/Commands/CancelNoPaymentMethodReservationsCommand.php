<?php

namespace App\Console\Commands;

use App\Jobs\CancelNoPaymentMethodReservationsJob;
use Illuminate\Console\Command;

class CancelNoPaymentMethodReservationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-no-payment-method-reservations-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel reservations without payment details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CancelNoPaymentMethodReservationsJob::dispatchSync();
    }
}
