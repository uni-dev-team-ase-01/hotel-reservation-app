<?php

namespace App\Console\Commands;

use App\Jobs\NoShowReservationsJob;
use Illuminate\Console\Command;

class NoShowReservationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:no-show-reservations-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        NoShowReservationsJob::dispatchSync();
    }
}
