<?php namespace App\Listeners;

use App\Events\ListingViewed;

use Illuminate\Session\Store;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class ListingViewedHandler {

	private $session;

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct(Store $session){
		//
		$this->session = $session;
	}

	/**
	 * Handle the event.
	 *
	 * @param  ListingViewed  $event
	 * @return void
	 */
	public function handle(ListingViewed $event){
		//
		if (!$this->isListingViewed($event->listing)){
	        $event->listing->increment('views');
	        $event->listing->view += 1;

	        $this->storeListing($event->listing);
	    }
	}

	private function isListingViewed($listing){
	    // Get all the viewed listings from the session. If no
	    // entry in the session exists, default to an
	    // empty array.
	    $viewed = $this->session->get('viewed_listings', []);

	    // Check the viewed listings array for the existance
	    // of the id of the listing
	    return array_key_exists($listing->id, $viewed);
	}

	private function storeListing($listing){    
	    // First make a key that we can use to store the timestamp
	    // in the session. Laravel allows us to use a nested key
	    // so that we can set the post id key on the viewed_posts
	    // array.
	    $key = 'viewed_listings.' . $listing->id;

	    // Then set that key on the session and set its value
	    // to the current timestamp.
	    $this->session->put($key, time());
	}

}
