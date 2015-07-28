<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Mail, Settings;
use App\Models\Payment;

class SendPaymentConfirmationEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	private $payment;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Payment $payment){
		//
		$this->payment = $payment;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle(){
		//
		$payment = $this->payment;
		
		Mail::send('emails.paymentConfirmation', ['payment' => $payment], function ($message) use ($payment) {
		    $message->from(Settings::get('email_from'), Settings::get('email_from_name'))
		    		->to($payment->listing->broker->email, $payment->listing->broker->name)
		    		//->cc($object->email)
		    		->subject(trans('emails.payment_confirmation_subject'));
		});
	}

}
