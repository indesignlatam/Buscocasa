<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Cookie;
use Carbon;
use Queue;
use Settings;
use Analytics;

use App\Models\Appointment;
use	App\Models\Listing;
use App\Jobs\SendNewMessageEmail;
use	App\Jobs\RespondMessageEmail;
use	App\Events\ListingMessaged;

class AppointmentController extends Controller {

	/**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('throttle', ['only' => ['store', 'answer']]);
        $this->middleware('recaptcha', ['only' => 'store']);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		$query = null;
		$take = Settings::get('pagination_objects');

		if(Auth::user()->is('admin')){
			// Create the principal query
			$query = Appointment::with('listing');
		}else{
			// Create the principal query
			$query = Appointment::leftJoin('listings',
									function($join) {
										$join->on('appointments.listing_id', '=', 'listings.id');
									})
								  ->select('appointments.id', 'name', 'email', 'phone', 'comments', 'read', 'answered', 'appointments.user_id', 'appointments.listing_id', 'listings.broker_id', 'appointments.created_at')
								  ->where('listings.broker_id', Auth::user()->id)
								  ->with('listing');
		}


		if(count($request->all()) > 0){
			if($request->has('search')){
				$search = $request->search;
				$query = $query->where('name', 'LIKE', "%$search%");
			}

			if($request->get('deleted')){
				$query = $query->onlyTrashed();
			}

			// Order the objects
			if($request->has('order_by')){
				if($request->get('order_by') == 'id_asc'){
					$query = $query->orderBy('id', 'ASC');
				}else if($request->get('order_by') == 'id_desc'){
					$query = $query->orderBy('id', 'DESC');
				}
			}else{
				$query = $query->orderBy('answered', 'ASC')->orderBy('appointments.created_at', 'DESC');
			}

			// Take n objects
			if($request->has('take')){
				$take = $request->get('take');
			}
		}else{
			$query = $query->orderBy('answered', 'ASC')->orderBy('appointments.created_at', 'DESC');
		}

		// Execute the query
		$objects = $query->paginate($take);

		return view('admin.appointments.index', ['appointments' => $objects]);
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
		// Get object to work with
		$appointment = new Appointment;

		// Validate the request
		if (!$appointment->validate($request->all())){
	        return redirect()->back()->withErrors($appointment->errors())->withInput();
	    }

	    $input = $request->all();
	    if(Auth::check()){
	    	$input['user_id'] = Auth::user()->id;
	    }
	    
	    // Create the object
		$appointment = $appointment->create($input);

		// Queue the Send Email command
		Queue::push(new SendNewMessageEmail($appointment));

		// Queue the cookie
		Cookie::queue('listing_message_'.$appointment->listing_id, date("Y-m-d H:i:s"), 86400);

		// Analytics event
		Analytics::trackEvent('Contact Vendor', 'button', $appointment->listing_id, 1);

		return redirect()->back()->withSuccess([trans('responses.message_success')]);
	}

	/**
	 * Display the messages from an specific listing.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, Request $request){
		// Get the object requested
		$listing = Listing::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker_id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Create the principal query
		$query 	= Appointment::where('listing_id', $id);
		$take 	= Settings::get('pagination_objects');

		// Order the objects
		if($request->get('order_by')){
			if($request->get('order_by') == 'id_asc'){
				$query = $query->orderBy('id', 'ASC');
			}else if($request->get('order_by') == 'id_desc'){
				$query = $query->orderBy('id', 'DESC');
			}
		}else{
			$query = $query->orderBy('answered', 'ASC')->orderBy('appointments.created_at', 'DESC');
		}

		// Take n objects
		if($request->has('take') && is_int($request->get('take'))){
			$take = $request->get('take');
		}

		// Execute the query
		$appointments = $query->with('listing', 'listing.images')->paginate($take);

		return view('admin.appointments.index', ['appointments' => $appointments,
												 'listing'		=> $listing
												 ]);
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
	 * Answer the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function answer($id, Request $request){
		// Get the object requested
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->broker_id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Set the message as readed and answered
		$message->answered 	= true;
		$message->read 		= true;
		$message->save();

		$comments = $request->get('comments');

		// Queue the Send Email command
		Queue::push(new RespondMessageEmail($comments, $message));

		// Analytics event
		Analytics::trackEvent('Answer Message', 'button', $message->id, 1);

		// Return the response in ajax or sync
		if($request->ajax()){
			return response()->json(['success' => trans('responses.message_sent')]);
		}
		return redirect('admin/appointments')->withSuccess([trans('responses.message_sent')]);
	}

	/**
	 * Mark or unmark the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function markAsRead($id, Request $request){
		// Get the object requested
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->broker_id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Set the message as readed and answered
		$message->read 		= $request->get('mark');
		$message->answered 	= $request->get('mark');
		$message->save();

		// Return the response in ajax or sync
		if($request->ajax()){
			return response()->json(['success'  => trans('responses.message_marked_'.(bool)$message->read),
									 'mark' 	=> (bool)$message->read,
									 ]);
		}
		return redirect('admin/appointments')->withSuccess([trans('responses.message_marked_'.(bool)$message->read)]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request){
		// Get the object requested
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->broker_id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Remove object
		$message->delete();

		// Return the response in ajax or sync
		if($request->ajax()){
			return response()->json(['success' => trans('responses.message_deleted')]);
		}
		return redirect('admin/appointments')->withSuccess([trans('responses.message_deleted')]);
	}

}