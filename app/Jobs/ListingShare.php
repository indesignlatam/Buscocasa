<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail, Settings;
use App\Models\Listing;

class ListingShare extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	private $listing;
	private $email;
	private $messageText;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Listing $listing, $email, $messageText){
		//
		$this->listing = $listing;
		$this->email = $email;
		$this->messageText = $messageText;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$listing = $this->listing;
		
		// If mail not confirmed dont send email
		if($listing->broker->confirmed){
			Mail::send('emails.listing_share', ['listing' 	=> $listing,
										  		'messageText'	=> $this->messageText,
										 ], 
			function ($message) use ($listing) {
			    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
			    		->to($this->email)
			    		//->cc($object->email)
			    		->replyTo($listing->broker->email)
			    		->subject($listing->broker->name.' '.trans('emails.listing_share_subject').$listing->code);
			});
		}
	}

}
