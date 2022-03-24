<?php

namespace App\Console;

use App\Http\Controllers\API\NotifsendController;
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
        $datas = Scheduletb::with(['user'])->distinct()->pluck('start_time');
        $datae = Scheduletb::with(['user'])->distinct()->pluck('end_time');
        foreach ($datas as $key => $value) {
            $schedule->call(function () {
                logger('start_time');
                NotifsendController::sendnotifstart();
            })->dailyAt($value);
        }
        foreach ($datae as $key => $value) {
            $schedule->call(function () {
                logger('end_time');
                NotifsendController::sendnotifend();
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
