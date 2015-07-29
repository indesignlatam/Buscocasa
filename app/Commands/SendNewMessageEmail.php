<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail, Settings;
use App\Models\Appointment;

class SendNewMessageEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $appointment;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Appointment $appointment){
		//
		$this->appointment = $appointment;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$object = $this->appointment;
		
		// If mail not confirmed dont send email
		if($object->listing->broker->confirmed){
			Mail::send('emails.message', ['userMessage' => $object], function ($message) use ($object) {
			    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
			    		->to($object->listing->broker->email, $object->listing->broker->name)
			    		//->cc($object->email)
			    		->replyTo($object->email)
			    		->subject(trans('emails.new_message_subject').$object->id);
			});
		}
	}

}
