<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail, Settings;
use App\Models\Listing;

class SendExpiringListingEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $listing;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Listing $listing){
		//
		$this->listing = $listing;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$listing = $this->listing;
		// If mail not confirmed dont send email
		if($listing->broker->confirmed){
			Mail::send('emails.listing_expiring', ['listing' => $listing], function ($message) use ($listing) {
			    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
						->to($listing->broker->email, $listing->broker->name)
			    		->subject(trans('emails.expiring_listing_subject'));
			});
		}
	}

}
