<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Carbon, Log, DB, Queue;
use App\Models\Listing;
use App\Jobs\SendExpiringListingEmail;

class MailExpiringListings extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'mail:expiring_listings';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Email all users that have an expiring listing so they can renovate it.';

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
		// Email all users where listings are expiring
		// Todo or where featured is expiring
		$listings = Listing::whereRaw('featured_expires_at < ? AND featured_expires_at > ?', [Carbon::now()->addDays(5), Carbon::now()])
						   ->where('expire_notified', false)
						   ->orWhereRaw('expires_at < ? AND expires_at > ?', [Carbon::now()->addDays(5), Carbon::now()])
						   ->where('expire_notified', false)
						   ->with('broker')
						   ->get();

		if(count($listings) > 0){
			$this->info('Started sending '.count($listings).' emails');
			// $this->output->progressStart(count($listings));//Only for laravel 5.1

			$ids = [];
			foreach ($listings as $listing) {
				$this->info('Sending email to '.$listing->broker->email);
				Queue::push(new SendExpiringListingEmail($listing));
				
				$ids[] = $listing->id;
				// $this->output->progressAdvance();//Only for laravel 5.1
			}

			DB::table('listings')
	            ->whereIn('id', $ids)
	            ->update(['expire_notified' => true]);
			// $this->output->progressFinish(); //Only for laravel 5.1
			$this->info('Finished sending emails');
		}else{
			$this->info('There are no expiring listings');
		}
	}

}
