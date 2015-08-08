<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail, Settings;
use App\Models\Listing;

class SendTipsEmail extends Command implements SelfHandling, ShouldBeQueued {

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

		// If mail account not confirmed dont send email
		if($listing->broker->confirmed){
			Mail::send('emails.tips', [ 'listing' => $listing, 
										'user' => $listing->broker,
									  ], 
			function ($message) use ($listing) {
			    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
						->to($listing->broker->email, $listing->broker->name)
			    		->subject(trans('emails.tips_subject'));
			});
		}
		
	}
}
