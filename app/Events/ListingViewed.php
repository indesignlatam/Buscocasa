<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use App\Models\Listing;

class ListingViewed extends Event {

	use SerializesModels;

	public $listing;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(Listing $listing){
		//
        $this->listing = $listing;
	}

}
