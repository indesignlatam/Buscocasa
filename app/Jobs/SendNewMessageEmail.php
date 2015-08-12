<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail, Settings;
use App\Models\Appointment;

class SendNewMessageEmail extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	private $appointment;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Appointment $appointment){
		//
		$this->appointment = $appointment;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$object = $this->appointment;
		
		// If mail not confirmed dont send email
		if($object->listing->broker->confirmed && $listing->broker->email_notifications){
			Mail::send('emails.message', ['userMessage' => $object, 
										  'user' 		=> $object->listing->broker,
										 ], 
			function ($message) use ($object) {
			    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
			    		->to($object->listing->broker->email, $object->listing->broker->name)
			    		//->cc($object->email)
			    		->replyTo($object->email)
			    		->subject(trans('emails.new_message_subject').$object->listing->code);
			});
		}
	}

}
