<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth, Session;

use App\Models\ListingType;

class ListingTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$objects = ListingType::paginate(30);

		return view('admin.listing-types.index', ['types' => $objects]);
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
		$device = new ListingType;

		if (!$device->validate($request->all())){
	        return redirect('admin/listing-types')->withErrors($device->errors());
	    }

		$device->create($request->all());

		return redirect('admin/listing-types')->withSuccess(['Listing type created succesfuly']);
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
		// for use only with js
		$device = ListingType::find($id);

		if(!$device){
			Session::flash('errors', ['No object found']);
			return;
		}

		$device->delete();
		Session::flash('success', ['Listing type Deleted']);
		return;
	}

}
