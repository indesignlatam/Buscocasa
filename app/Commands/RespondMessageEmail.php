<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail, Log, Settings;
use App\Models\Appoinment;

class RespondMessageEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	protected $messageToAnswer;
	protected $comments;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($comments, $messageToAnswer){
		//
		$this->comments 			= $comments;
		$this->messageToAnswer 		= $messageToAnswer;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$messageToAnswer 	= $this->messageToAnswer;
		$comments 			= $this->comments;

		// If mail not confirmed dont send email
		if($messageToAnswer->listing->broker->confirmed){
			Mail::send('emails.message_answer', ['messageToAnswer' 	=> $messageToAnswer, 
												 'comments' 		=> $comments, 
												 'user' 			=> $messageToAnswer,
												],
			function ($message) use ($messageToAnswer) {
			    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
			    		->replyTo($messageToAnswer->listing->broker->email)
						->to($messageToAnswer->email, $messageToAnswer->name)
			    		->subject(trans('emails.message_answer_subject'));
			});
		}
	}

}
