<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Cookie;

class CookieController extends Controller {

	/**
	 * Display the specified resource.
	 *
	 */
	public function __construct(){
        // 
	}

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

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function postSelect(Request $request){
		//
		if ($cookie_data = Cookie::get($request->get('key'))) {
            if(!is_array($cookie_data)){
                $data 	= [];
                $data[] = $cookie_data;
            }else{
                $data = $cookie_data;
            }

            if(!in_array($request->get('value'), $data)){
            	array_push($data, $request->get('value'));
            	if(count($data) > 4){
            		array_shift($data);
            	}
            }
        }else{
            $data = $request->get('value');
        }


		if($request->has('time')){
			Cookie::queue($request->get('key'), $data, $request->get('time'));
		}else{
			Cookie::queue($request->get('key'), $data);
		}

	    return response()->json(['success' 	=> true, 
	    						 'key' 		=> $request->get('key'), 
	    						 'value' 	=> $request->get('value'),
	    						 'time'		=> $request->get('time'),
	    						 'array' 	=> Cookie::get($request->get('key')),
	    						]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function postForget(Request $request){
		//
		$cookie = Cookie::forget($request->get('key'));

		return response()->json(['success' 	=> true, 
	    					  	 'key' 		=> $request->get('key'), 
	    					 	])->withCookie($cookie);
	}
}