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
		'App\Console\Commands\MailExpiringListings',
		'App\Console\Commands\TipsEmail',
		'App\Console\Commands\CleanTempFolder',
		'App\Console\Commands\DeleteExpiredListings',
		'App\Console\Commands\ArchiveDeletedListings',
		'App\Console\Commands\TestPaymentEmail',
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

		$schedule->command('listings:archive')
				 ->dailyAt('1:00');

		$schedule->command('mail:tips')
				 ->dailyAt('5:30');

		$schedule->command('images:clean_temp')
				 ->dailyAt('3:00');
	}

}
