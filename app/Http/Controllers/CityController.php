<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth, Session;
use App\Models\City, App\Models\Country;

class CityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$objects = City::paginate(30);

		$categories = Country::all();

		return view('admin.cities.index', ['cities' => $objects, 'countries' => $categories]);
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
		$device = new City;

		if (!$device->validate($request->all())){
	        return redirect('admin/cities')->withErrors($device->errors());
	    }

		$device->create($request->all());

		return redirect('admin/cities')->withSuccess(['City created succesfuly']);
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
		$device = City::find($id);

		if(!$device){
			Session::flash('errors', ['No object found']);
			return;
		}

		$device->delete();
		Session::flash('success', ['City Deleted']);
		return;
	}

}
