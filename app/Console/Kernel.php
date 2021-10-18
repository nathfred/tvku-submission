<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Submission;
use Illuminate\Support\Facades\Log;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $today = Carbon::now('GMT+7');

            // HAPUS SUBMISSION YANG SUDAH 2 HARI TIDAK DIRESPON HRD
            Submission::where('created_at', '<', Carbon::now('GMT+7')->subDays(2))->whereNull('hrd_approval')->orWhereNull('division_approval')->delete();
            Submission::where('created_at', '<', Carbon::now('GMT+7')->subDays(2))->Where('hrd_approval', 0)->orWhere('division_approval', 0)->delete();

            Log::info('Daily Cronjob Successfully Run : ' . $today->toDateTimeString());
        })->daily()->timezone('Asia/Jakarta');

        $schedule->call(function () {
            $today = Carbon::now('GMT+7');

            // HAPUS SUBMISSION TIAP TAHUN BARU
            Submission::truncate();

            Log::info('Yearly Cronjob Successfully Run : ' . $today->toDateTimeString());
        })->yearly()->timezone('Asia/Jakarta');
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
