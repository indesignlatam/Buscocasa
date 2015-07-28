<?php namespace App\Http\Middleware;

use Closure;

use Illuminate\Session\Store;

class ListingViewThrottle {

	private $session;

	public function __construct(Store $session){
        // Let Laravel inject the session Store instance,
        // and assign it to our $session variable.
        $this->session = $session;
    }

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next){
		$listings = $this->getViewedListings();

        if (!is_null($listings)){
            $listings = $this->cleanExpiredViews($listings);

            $this->storeListings($listings);
        }

		return $next($request);
	}

	private function getViewedListings(){
        // Get all the viewed posts from the session. If no
        // entry in the session exists, default to null.
        return $this->session->get('viewed_listings', null);
    }

    private function cleanExpiredViews($listings){
        $time = time();

	    // Let the views expire after one hour.
	    $throttleTime = 1800;

	    // Filter through the post array. The argument passed to the
	    // function will be the value from the array, which is the
	    // timestamp in our case.
	    return array_filter($listings, function ($timestamp) use ($time, $throttleTime){
	        // If the view timestamp + the throttle time is 
	        // still after the current timestamp the view  
	        // has not expired yet, so we want to keep it.
	        return ($timestamp + $throttleTime) > $time;
	    });
    }

    private function storeListings($listings){
        $this->session->put('viewed_listings', $listings);
    }

}
