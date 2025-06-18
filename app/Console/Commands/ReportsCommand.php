<?php

namespace App\Console\Commands;

use App\Jobs\DailyRevenueReportsJob;
use App\Jobs\RoomRevenueReportsJob;
use Illuminate\Console\Command;

class ReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reports-command';

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
        DailyRevenueReportsJob::dispatchSync();
        RoomRevenueReportsJob::dispatchSync();
    }
}
