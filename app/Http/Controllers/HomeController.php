<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth, Carbon, Settings;

use App\Models\Appointment;
use App\Models\Listing;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	/**
     * Instantiate a new UserController instance.
     */
    public function __construct(){
        $this->middleware('auth');

        //$this->middleware('log', ['only' => ['fooAction', 'barAction']]);

        //$this->middleware('subscribed', ['except' => ['fooAction', 'barAction']]);
    }

	public function index(){

		if(Auth::user()->is('admin')){
			return view('admin.home.home');
		}elseif(Auth::user()->confirmed){
			$messages 				= Appointment::remember(Settings::get('query_cache_time_extra_short'))
											  ->leftJoin('listings',
												function($join) {
													$join->on('appointments.listing_id', '=', 'listings.id');
												})
											  ->select('appointments.id', 'name', 'email', 'phone', 'comments', 'read', 'answered', 'appointments.user_id', 'appointments.listing_id', 'listings.broker_id')
											  ->unread()
											  ->notAnswered()
											  ->where('listings.broker_id', Auth::user()->id)
											  ->orderBy('appointments.id', 'DESC')
											  ->with('listing')
											  ->take(8)
											  ->get();

			$listings 				= Listing::whereRaw('broker_id = ? AND featured_expires_at < ? AND featured_expires_at > ?', [Auth::user()->id, Carbon::now()->addDays(5), Carbon::now()])
											 ->orWhereRaw('broker_id = ? AND expires_at < ? AND expires_at > ?', [Auth::user()->id, Carbon::now()->addDays(5), Carbon::now()])
											 ->orderBy('expires_at', 'DESC')
											 ->with('messageCount')
											 ->take(8)
											 ->get();

			$listingsAll 			= Listing::remember(Settings::get('query_cache_time_extra_short'))
											 ->where('broker_id', Auth::user()->id)
											 ->get();

			$notAnsweredMessages 	= Appointment::remember(Settings::get('query_cache_time_extra_short'))
												  ->leftJoin('listings',
													function($join) {
														$join->on('appointments.listing_id', '=', 'listings.id');
													})
												  ->select('appointments.id', 'read', 'answered', 'appointments.user_id', 'appointments.listing_id', 'listings.broker_id')
												  ->notAnswered()
												  ->where('listings.broker_id', Auth::user()->id)
												  ->count();


			$colors = ['#F7464A','#46BFBD','#FDB45C','#949FB1','#4D5360','#FF9500','#52EDC7','#EF4DB6','#4CD964','#FFCC00','#5856D6','#FF3A2D','#C86EDF','#007AFF','#FF2D55','#FF9500','#0BD318',];
			$data = [];
			foreach ($listingsAll as $key => $listing) {
				# code...
				$data[] = [ 'value' => $listing->views,
							'color' => $colors[rand(0, 16)],
							'highlight' => "#FF5A5E",
							'label' => $listing->title,
						  ];
			}

			return view('admin.home.dashboard', ['messages' 	=> $messages,
												 'listings' 	=> $listings,
												 'listingCount' => count($listingsAll),
												 'notAnsweredMessages' 	=> $notAnsweredMessages,
												 'data' 		=> $data
												 ]);
		}
		
		return redirect('/admin/listings');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		//
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		//
		
	}

}
