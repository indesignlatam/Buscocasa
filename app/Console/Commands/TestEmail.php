<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Queue;
use App\Models\Payment;
use App\Models\Listing;
use App\Models\Appointment;
use App\User;

use App\Jobs\SendPaymentConfirmationEmail;
use App\Jobs\SendUserConfirmationEmail;
use App\Jobs\SendTipsEmail;
use App\Jobs\SendNewMessageEmail;
use App\Jobs\RespondMessageEmail;
use App\Jobs\ListingShare;
use App\Jobs\SendExpiringListingEmail;

class TestEmail extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'mail:test {--payment} {--confirm} {--tips} {--message} {--answer} {--expiring} {--share}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Test emails, payment, confirm, tips, message, answer, expiring, share';

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
		if($this->option('payment')){
			$payment = Payment::first();
			// Send confirmation email to user and generate billing
			Queue::push(new SendPaymentConfirmationEmail($payment));
		}
		
		if($this->option('confirm')){
			$user = User::where('email', 'wanchopeblanco@gmail.com')->first();
			Queue::push(new SendUserConfirmationEmail($user));
		}

		if($this->option('tips')){
			$user = User::where('email', 'wanchopeblanco@gmail.com')->first();
			$listing = Listing::active()->where('broker_id', $user->id)->first();
			$this->info($listing->title);
			Queue::push(new SendTipsEmail($listing));
		}

		if($this->option('message')){
			$user = User::where('email', 'wanchopeblanco@gmail.com')->first();
			$listing = Listing::active()->where('broker_id', $user->id)->first();

			$message = Appointment::where('listing_id', $listing->id)->first();

			Queue::push(new SendNewMessageEmail($message));
		}

		if($this->option('answer')){
			$user = User::where('email', 'wanchopeblanco@gmail.com')->first();
			$listing = Listing::active()->where('broker_id', $user->id)->first();

			$message = Appointment::where('listing_id', $listing->id)->first();
			$comments = "Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas, las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.";
			Queue::push(new RespondMessageEmail($comments, $message));
		}

		if($this->option('expiring')){
			$user = User::where('email', 'wanchopeblanco@gmail.com')->first();
			$listing = Listing::active()->where('broker_id', $user->id)->first();

			Queue::push(new SendExpiringListingEmail($listing));
		}

		if($this->option('share')){
			$user = User::where('email', 'wanchopeblanco@gmail.com')->first();
			$listing = Listing::active()->where('broker_id', $user->id)->first();

			$message = "Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas, las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.";
			Queue::push(new ListingShare($listing, "wanchopeblanco@gmail.com", $message));
		}
	}
	
}
