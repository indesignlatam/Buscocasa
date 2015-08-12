<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use File;

class FlushCache extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'cache:flush {--views} {--sessions} {--logs} {--all}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deletes cache files from cache, views, sessions, logs.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){
		//
		$cacheDirectory 	= storage_path().'/framework/cache';
		$viewsDirectory 	= storage_path().'/framework/views';
		$sessionsDirectory 	= storage_path().'/framework/sessions';
		$logsDirectory 		= storage_path().'/logs';


		if ($this->confirm('Do you wish to continue? [yes|no]')){
		    // Clean cache directory
		    $deletedCache = File::cleanDirectory($cacheDirectory);

			if($deletedCache){
				$this->info('Deleted cache');
				$bytes_written = File::put($cacheDirectory.'/.gitignore', '*'.PHP_EOL.'!.gitignore');
				if($bytes_written === false){
					$this->error('Error creating .gitignore for cache');
				}
			}else{
				$this->error('Error deleting cache');
			}

		    // Clean views directory
			if($this->option('views') || $this->option('all')){
				$deletedViews = File::cleanDirectory($viewsDirectory);

				if($deletedViews){
					$this->info('Deleted views');
					$bytes_written = File::put($viewsDirectory.'/.gitignore', '*'.PHP_EOL.'!.gitignore');
					if($bytes_written === false){
						$this->error('Error creating .gitignore for views');
					}
				}else{
					$this->error('Error deleting views');
				}
			}

		    // Clean logs directory
			if($this->option('logs') || $this->option('all')){
				$deletedLogs = File::cleanDirectory($logsDirectory);

				if($deletedLogs){
					$this->info('Deleted logs');
					$bytes_written = File::put($logsDirectory.'/.gitignore', '*'.PHP_EOL.'!.gitignore');
					if($bytes_written === false){
						$this->error('Error creating .gitignore for logs');
					}
				}else{
					$this->error('Error deleting logs');
				}
			}

			// Clean logs directory
			if($this->option('sessions') || $this->option('all')){
				$deletedSessions = File::cleanDirectory($sessionsDirectory);

				if($deletedSessions){
					$this->info('Deleted sessions');
					$bytes_written = File::put($sessionsDirectory.'/.gitignore', '*'.PHP_EOL.'!.gitignore');
					if($bytes_written === false){
						$this->error('Error creating .gitignore for sessions');
					}
				}else{
					$this->error('Error deleting sessions');
				}
			}
		}
	}

}
