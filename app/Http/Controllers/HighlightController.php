<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Settings;
use Auth;
use App\Models\Listing;
use	App\Models\FeaturedType;

class HighlightController extends Controller {

	private $path;

	public function __construct(){
		$this->path = '/admin/destacar/';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
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
	public function show($id, Request $request){
		// Get the object requested
		$listing = Listing::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker_id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Get the featured types and cache them
		$featuredTypes = FeaturedType::remember(Settings::get('query_cache_time'))->get();

		// Return the view
		return view('admin.highlight.create', [ 'listing' 		=> $listing,
												'featuredTypes' => $featuredTypes
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		//
	}

}