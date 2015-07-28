<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth, Queue;

use App\User;
use App\Commands\SendUserConfirmationEmail;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$users = User::paginate(30);
		return view('admin.users.index', ['users' => $users]);
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
	public function store(){
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($username){
		//
		$user = User::where('username', $username)->first();

		if(!$user){
			$user = User::find($username);

			if(!$user){
				return redirect('/')->withErrors(['No se encontro ningun usuario']);
			}
		}

		$user->load('listings', 'listings.listingType', 'listings.featuredType');

		return view('user.show', ['user' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		if($request->ajax()){
					Session::flash('success', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/user/'.Auth::user()->id.'/edit')->withErrors([trans('responses.no_permission')]);
	    	}
		}

	    $user = User::find($id);

		return view('admin.users.edit', ['user' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request){
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		if($request->ajax()){
					Session::flash('error', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/user/'.Auth::user()->id.'/edit')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$user 		= new User;

		$input 					= $request->all();
		$input['phone_1'] 		= preg_replace("/[^0-9]/", "", $input['phone_1']);
		$input['phone_2'] 		= preg_replace("/[^0-9]/", "", $input['phone_2']);
		$input['description'] 	= preg_replace("/[^a-zA-Z0-9.,?¿#%&ñáéíóú ]+/", "", $input['description']);

		if (!$user->validate($input, null, null, $id)){
	        return redirect('/admin/user/'.$id.'/edit')->withErrors($user->errors());
	    }

	    $user = User::find($id);

		$user->fill($input)->save();

		return redirect('/admin/user/'.$id.'/edit')->withSuccess([trans('responses.user_saved')]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function confirm($id, $code){
		//
	    $user = User::where('id', $id)->where('confirmation_code', $code)->first();
	    $user->confirmed 			= true;
	    $user->confirmation_code 	= null;
	    $user->save();

	    Auth::login($user);
		return redirect('/admin/user/'.$id.'/edit')->withSuccess([trans('responses.account_confirmed'), trans('responses.complete_profile')]);
	}

	public function sendConfirmationEmail(){
		if(!Auth::user()->confirmed){
			Auth::user()->confirmation_code = str_random(64);
			Auth::user()->save();

			Queue::push(new SendUserConfirmationEmail(Auth::user()));

			return view('admin.users.confirmation_sent');
		}

		return redirect('admin')->withErrors([trans('responses.no_permission')]);
	}

	public function notConfirmed(){
		if(!Auth::user()->confirmed){
			return view('admin.users.not_confirmed');
		}else{
			return redirect('admin');
		}
	}



	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		$self = false;
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		$self = true;
	    		if($request->ajax()){
					Session::flash('error', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/user/'.Auth::user()->id.'/edit')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		if($self){
			$user = Auth::user();
			$user->delete();

			Auth::logout();
			return redirect('/')->withSuccess([trans('responses.your_account_removed')]);
		}

		$user = User::find($id);
		$user->delete();

		return redirect('/admin/users')->withSuccess([trans('responses.account_removed')]);
	}

}
