<?php

namespace App\Console;

use App\Models\Schedule as Scheduletb;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $data = Scheduletb::with(['user'])->get();
        foreach ($data->pluck('start_time') as $key => $value) {
            $schedule->call(function () {
            })->dailyAt($value);
        }
        foreach ($data->pluck('end_time') as $key => $value) {
            $schedule->call(function () {
            })->dailyAt($value);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
