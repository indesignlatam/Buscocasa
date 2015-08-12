<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail, Log, Settings;
use App\User;

class SendUserConfirmationEmail extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	private $user;

	/**
     * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(User $user){
		//
		$this->user = $user;
	}

	/**
     * Execute the job.
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
