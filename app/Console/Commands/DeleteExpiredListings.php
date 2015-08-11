<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Carbon, DB;
use App\Models\Listing;

class DeleteExpiredListings extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'listings:delete_expired';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command to archive all the expired listings that has more than n days expired.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command. ayer 1 >  hoy 2
	 *
	 * @return mixed
	 */
	public function fire(){
		//
		$listings = Listing::whereRaw('{fn TIMESTAMPDIFF( DAY, expires_at, now() )} > 10')// Set 20 to what ever i need
                     	   ->get();

        $this->info('Listings to delete: '.count($listings));
        // $this->output->progressStart(count($listings));//Only for laravel 5.1

        foreach ($listings as $listing) {
        	$listing->delete();
        	$this->info('Listing: '.$listing->id);
        	// $this->output->progressAdvance();//Only for laravel 5.1
        }

        $this->info('Listings succesfuly deleted');
        // $this->output->progressFinish(); //Only for laravel 5.1
	}

}
