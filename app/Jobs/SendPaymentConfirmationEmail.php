<?php namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail, Settings;
use App\Models\Payment;

class SendPaymentConfirmationEmail extends Job implements SelfHandling, ShouldQueue {

	use InteractsWithQueue, SerializesModels;

	private $payment;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Payment $payment){
		//
		$this->payment = $payment;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$payment = $this->payment;

		Mail::send('emails.payment_confirmation', ['payment' 	=> $payment, 
												   'user' 		=> $payment->user,
												   ], 
		function ($message) use ($payment) {
		    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
		    		->to($payment->user->email, $payment->user->name)
		    		//->cc($object->email)
		    		->subject(trans('emails.payment_confirmation_subject'));
		});
	}

}
