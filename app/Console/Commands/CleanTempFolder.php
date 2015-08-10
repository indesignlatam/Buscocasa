<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use File;

class CleanTempFolder extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'images:clean_temp';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command to clean the images temp folder.';

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
		$files = File::allFiles(public_path().'/images/temp');
		$this->info('Temp files to delete: '.count($files));

		foreach ($files as $file) {
			// Delete each temp file
			File::delete($file);
		}

		$this->info('Files deleted: '.count($files));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments(){
		return [
			// ['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions(){
		return [
			// ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
