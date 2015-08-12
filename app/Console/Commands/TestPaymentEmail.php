<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Queue;
use App\Models\Payment;
use App\Jobs\SendPaymentConfirmationEmail;

class TestPaymentEmail extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'mail:test_payment';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){
		$payment = Payment::first();
		// Send confirmation email to user and generate billing
		Queue::push(new SendPaymentConfirmationEmail($payment));
	}
	
}
