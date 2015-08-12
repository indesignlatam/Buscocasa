<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Carbon, DB, File;
use App\Models\Listing;
use App\Models\ArchivedListing;

class ArchiveDeletedListings extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'listings:archive';

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
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){
		//
		$listings = Listing::whereRaw('{fn TIMESTAMPDIFF( DAY, deleted_at, now() )} > 8')// Set 20 to what ever i need
                     	   ->onlyTrashed()
                     	   ->get();

        if(count($listings) > 0){
        	$this->info('Listings to archive: '.count($listings));
	        // $this->output->progressStart(count($listings));//Only for laravel 5.1

	        foreach ($listings as $listing) {
	        	$this->info('Archiving listing: '.$listing->id);
	        	// $this->output->progressAdvance();//Only for laravel 5.1

				// Permantly delete form the table
	        	$listing->forceDelete();
	        }

	        $this->info('Listings succesfuly archived');
	        // $this->output->progressFinish(); //Only for laravel 5.1
        }else{
        	$this->info('No listings to be archived found');
        }
	}

}