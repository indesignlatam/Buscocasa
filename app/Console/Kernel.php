<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\MailExpiringListings::class,
        \App\Console\Commands\TipsEmail::class,
        \App\Console\Commands\CleanTempFolder::class,
        \App\Console\Commands\DeleteExpiredListings::class,
        \App\Console\Commands\ArchiveDeletedListings::class,
        \App\Console\Commands\TestEmail::class,
        \App\Console\Commands\FlushCache::class,
        \App\Console\Commands\NullFeaturedTypeFromExpiredListing::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){
        $schedule->command('mail:expiring_listings')
                 ->dailyAt('5:00');

        $schedule->command('mail:tips')
                 ->dailyAt('7:00');

        $schedule->command('listings:archive')
                 ->dailyAt('1:10');

        $schedule->command('images:clean_temp')
                 ->dailyAt('1:00');

        $schedule->command('listings:null_featured_expired')
                 ->hourly();
    }
}
