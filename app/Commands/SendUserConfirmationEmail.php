<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail, Log, Settings;
use App\User;

class SendUserConfirmationEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $user;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(User $user){
		//
		$this->user = $user;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$user = $this->user;

		Mail::send('emails.confirm_user', ['user' => $user], function ($message) use ($user) {
		    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
					->to($user->email, $user->name)
		    		->subject(trans('emails.user_confirmation_subject'));
		});
	}

}
