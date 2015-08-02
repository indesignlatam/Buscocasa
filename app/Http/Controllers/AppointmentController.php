<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth, Cookie, Carbon, Queue, Settings, Analytics;
use App\Models\Appointment,
	App\Models\Listing;
use App\Commands\SendNewMessageEmail,
	App\Commands\RespondMessageEmail;

use	App\Events\ListingMessaged;

class AppointmentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		$query;
		if(Auth::user()->is('admin')){
		}else{
			$query = Appointment::leftJoin('listings',
									function($join) {
										$join->on('appointments.listing_id', '=', 'listings.id');
									})
								  ->select('appointments.id', 'name', 'email', 'phone', 'comments', 'read', 'answered', 'appointments.user_id', 'appointments.listing_id', 'listings.broker_id', 'appointments.created_at')
								  ->where('listings.broker_id', Auth::user()->id);
		}

		if($request->get('order_by')){
			if($request->get('order_by') == 'id_asc'){
				$query 		= $query->orderBy('id', 'ASC');
			}else if($request->get('order_by') == 'id_desc'){
				$query 		= $query->orderBy('id', 'DESC');
			}					
		}else{
			$query 	= $query->with('listing')->orderBy('answered', 'ASC')->orderBy('appointments.created_at', 'DESC');
		}

		$objects = $query->paginate(Settings::get('pagination_objects'));

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
		
		$object 	= new Appointment;

		$listing 	= Listing::find($request->get('listing_id'));
		$url 		= $listing->path();

		if(!Auth::check()){
			if(!$request->has('g-recaptcha-response')){
				return redirect($url)->withErrors([trans('auth.recaptcha_error')])->withInput();
			}

			// Captcha verify
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 
			         http_build_query(['secret' 	=> '6Ldv5wgTAAAAAKsrEHnUTD2wKdUtrfQUxFo_S3lq',
			         					'response' 	=> $request->get('g-recaptcha-response'),
			         					'remoteip'	=> $request->getClientIp()
			         					]));

			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$captcha = curl_exec($ch);
			$captcha = json_decode($captcha, true);
			curl_close ($ch);
			// Captcha verify

			if(!$captcha['success']){
				return redirect($url)->withErrors([trans('auth.youre_bot')])->withInput();
			}
		}

		// Removes honeypot protection bug #162
		// if($request->has('surname') || $request->get('surname')){
		// 	return redirect($url)->withSuccess([trans('responses.message_success')]);
		// }

		$input 					= $request->all();
		$input['comments'] 		= preg_replace("/[^a-zA-Z0-9.,?¿ñáéíóú ]+/", "", $input['comments']);

		if (!$object->validate($input)){
	        return redirect($url)->withErrors($object->errors())->withInput();
	    }

		$object = $object->create($input);
		$object->load('listing', 'listing.broker');

		Queue::push(new SendNewMessageEmail($object));

		Cookie::queue('listing_message_'.$listing->id, Carbon::now(), 86400);

		// Analytics event
		Analytics::trackEvent('Contact Vendor', 'button', $listing->id);

		return redirect($url)->withSuccess([trans('responses.message_success')]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, Request $request){
		//
		$listing = Listing::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$query = Appointment::where('listing_id', $id);

		if($request->get('order_by')){
			if($request->get('order_by') == 'id_asc'){
				$query 		= $query->orderBy('id', 'ASC');
			}else if($request->get('order_by') == 'id_desc'){
				$query 		= $query->orderBy('id', 'DESC');
			}					
		}else{
			$query 	= $query->orderBy('id', 'DESC');
		}

		$appointments = $query->with('listing', 'listing.images')->paginate(Settings::get('pagination_objects'));

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
		//
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$message->answered 	= true;
		$message->read 		= true;
		$message->save();

		$comments = $request->get('comments');

		Queue::push(new RespondMessageEmail($comments, $message));

		// Analytics event
		Analytics::trackEvent('Answer Message', 'button', $message->id);

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
		//
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$message->read 		= $request->get('mark');
		$message->answered 	= $request->get('mark');
		$message->save();

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
		//
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$message->delete();

		if($request->ajax()){
			return response()->json(['success' => trans('responses.message_deleted')]);
		}
		return redirect('admin/appointments')->withSuccess([trans('responses.message_deleted')]);
	}

}
