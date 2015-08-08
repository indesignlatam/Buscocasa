<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Cookie;

class CookieController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function postSet(Request $request){
		//
		if($request->has('time')){
			Cookie::queue($request->get('key'), $request->get('value'), $request->get('time'));
		}else{
			Cookie::queue($request->get('key'), $request->get('value'));
		}

	    return response()->json(['success' 	=> true, 
	    						 'key' 		=> $request->get('key'), 
	    						 'value' 	=> $request->get('value'),
	    						 'time'		=> $request->get('time'),
	    						]);
	}
}