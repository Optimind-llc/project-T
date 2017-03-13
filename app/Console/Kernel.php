<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\InsertInline',
        'App\Console\Commands\UpdateInline',
        'App\Console\Commands\InsertDummyData',
        'App\Console\Commands\Insert950ADummyData',
        'App\Console\Commands\Insert950AInline',
        'App\Console\Commands\Update950AInline',
        'App\Console\Commands\ExportCSV',
        'App\Console\Commands\PrintPDF'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
