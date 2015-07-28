<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth, Session, Gmaps, File, Image, Geocoder, Carbon, Settings;
use App\Models\Listing, App\Models\Category, 
	App\Models\ListingType, App\Models\ListingStatus, 
	App\Models\Feature, App\Models\City, App\Models\FeaturedType, App\User;

use App\Models\Image as ImageModel;

class ListingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		$query = Listing::with('city');

		if(Auth::user()->isAdmin()){
			if($request->get('deleted')){
				$query = Listing::onlyTrashed();
			}
		}else{
			if($request->get('deleted')){
				$query = Listing::where('broker_id', Auth::user()->id)
								  ->onlyTrashed();
			}else{
				$query = Listing::where('broker_id', Auth::user()->id);
			}
		}

		if($request->get('order_by')){
			if($request->get('order_by') == 'exp_desc'){
				$query 		= $query->orderBy('expires_at', 'DESC');
			}else if($request->get('order_by') == 'id_desc'){
				$query 		= $query->orderBy('id', 'DESC');
			}					
		}else{
			$query 	= $query->orderBy('id', 'DESC');
		}

		$objects = $query->with('listingType', 'featuredType','images', 'features')->paginate(Settings::get('pagination_objects'));

		return view('admin.listings.index', ['listings' => $objects]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request){
		//

		// Max free listings limit
		if(!Auth::user()->confirmed && Auth::user()->freeListingCount > 0){
			return redirect('admin/user/not_confirmed');
		}else if(Auth::user()->confirmed && Auth::user()->freeListingCount >= Settings::get('free_listings_limit', 10)){
			return redirect('admin/listings/limit');
		}

		$categories 		= Category::remember(Settings::get('query_cache_time'))->get();
		$listingTypes 		= ListingType::remember(Settings::get('query_cache_time'))->get();
		$features 			= Feature::remember(Settings::get('query_cache_time'))->with('category')->get();
		$cities 			= City::remember(Settings::get('query_cache_time'))->with('department')->get();

		$config = array();
		$config['scrollwheel'] 	= false;
	    $config['center'] = 'auto';
	    $config['zoom'] = '13';
	    $config['onboundschanged'] = 'if (!centreGot) {
	            var mapCentre = map.getCenter();
	            marker_0.setOptions({
	                position: new google.maps.LatLng(mapCentre.lat(), mapCentre.lng())
	            });
	        }
	        centreGot = true;';

	    $config['places'] = TRUE;
	    $config['placesComponentRestrictions']	= '{country: "co"}';
		$config['placesAutocompleteInputID'] 	= 'gmap_search';
		$config['placesAutocompleteBoundsMap'] 	= TRUE; // set results biased towards the maps viewport  createMarker_map({ map: map, place });
		$config['placesAutocompleteOnChange'] 	= 'var place = placesAutocomplete.getPlace().geometry.location; map.panTo(place); marker_0.setPosition(place); document.getElementById("latitude").value = place.lat(); document.getElementById("longitude").value = place.lng()';

	    $marker = array();
		$marker['position'] = 'map.getCenter()';
		$marker['draggable'] = true;
		$marker['ondragend'] = 'document.getElementById("latitude").value = event.latLng.lat(); document.getElementById("longitude").value = event.latLng.lng()';
		Gmaps::add_marker($marker);

	    Gmaps::initialize($config);

	    // set up the marker ready for positioning
	    // once we know the users location
	    $marker = array();
	    Gmaps::add_marker($marker);
	    $map = Gmaps::create_map();

		return view('admin.listings.new', [ 'categories' 		=> $categories, 
											'listingTypes' 		=> $listingTypes, 
											'cities' 			=> $cities,
											'features' 			=> $features, 
											'map' 				=> $map
											]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		// Max free listings limit
		if(!Auth::user()->confirmed && Auth::user()->freeListingCount > 0){
			return redirect('admin/not_confirmed');
		}else if(Auth::user()->confirmed && Auth::user()->freeListingCount >= Settings::get('free_listings_limit', 10)){
			return redirect('admin/listings/limit');
		}

		$listing = new Listing;

		$input 				= $request->all();
		$input['price'] 	= preg_replace("/[^0-9]/", "", $input['price']);
		$input['rooms'] 	= preg_replace("/[^0-9]/", "", $input['rooms']);
		$input['bathrooms'] = preg_replace("/[^0-9]/", "", $input['bathrooms']);
		$input['area'] 		= preg_replace("/[^0-9]/", "", $input['area']);
		$input['lot_area'] 	= preg_replace("/[^0-9]/", "", $input['lot_area']);
		$input['stratum'] 	= preg_replace("/[^0-9]/", "", $input['stratum']);
		$input['garages'] 	= preg_replace("/[^0-9]/", "", $input['garages']);
		$input['floor'] 	= preg_replace("/[^0-9]/", "", $input['floor']);
		$input['construction_year'] 	= preg_replace("/[^0-9]/", "", $input['construction_year']);
		$input['administration'] 		= preg_replace("/[^0-9]/", "", $input['administration']);
		$input['district'] 				= preg_replace("/[^a-zA-Z0-9ñáéíóú ]+/", "", $input['district']);
		$input['description'] 			= preg_replace("/[^a-zA-Z0-9.,?¿ñáéíóú ]+/", "", $input['description']);
		$input['direction'] 			= preg_replace("/[^a-zA-Z0-9# -]+/", "", $input['direction']);
		$input['broker_id'] 			= Auth::user()->id;
		$input['listing_status'] 		= 2;
		

		if(!$input['district']){
			$input['district'] = Geocoder::reverse($input['latitude'], $input['longitude'])->getCityDistrict();
		}

		if (!$listing->validate($input)){
	        return redirect('admin/listings/create')->withErrors($listing->errors())->withInput();
	    }

		$listing = $listing->create($input);


		$unwanted_array = [ 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' ];
		$district = strtr( $listing->district, $unwanted_array );

		// Create the title and slug of the listing
		$title 				= ucfirst(strtolower(str_singular($listing->category->name) . 
				 			  ' en ' . //TODO translate
				 			  $listing->listingType->name . 
							  ', ' .
							  $district)) .
							  ' ' .  
							  $listing->city->name;
							  
		$listing->title 	= str_limit($title, $limit = 245, $end = '');
		$listing->slug 		= str_limit(str_slug($listing->title.'-'.'15638'.$listing->id, '-'), $limit = 245, $end = '');

		// Set expiring date
		$listing->expires_at = Carbon::now()->addDays(Settings::get('listing_expiring'));

		// Set listing features
		$features = Feature::all();
	    $featuresSelected = [];
	    foreach ($features as $feature) {
	    	if($request->has($feature->id)){
	    		$featuresSelected[] = $feature->id;
	    	}
	    }
	    $listing->features()->attach($featuresSelected);

	    $listing->save();

		return redirect('admin/listings/'.$listing->id.'/edit')->withSuccess([trans('responses.listing_created')]);
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
		$listing = Listing::find($id);// Eager loading is not nesessary when only getting one object

		// Security check
	    if(!Auth::user()->isAdmin()){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$categories 		= Category::remember(Settings::get('query_cache_time'))->get();
		$listingTypes 		= ListingType::remember(Settings::get('query_cache_time'))->get();
		$features 			= Feature::remember(Settings::get('query_cache_time'))->with('category')->get();
		$cities 			= City::remember(Settings::get('query_cache_time'))->with('department')->get();

		$config = array();
		$config['scrollwheel'] 	= false;
	    $config['center'] = $listing->latitude.','.$listing->longitude;
	    $config['zoom'] = '15';

	    $config['places'] = TRUE;
	    $config['placesComponentRestrictions'] 	= '{country: "co"}';
		$config['placesAutocompleteInputID'] 	= 'gmap_search';
		$config['placesAutocompleteBoundsMap'] 	= TRUE; // set results biased towards the maps viewport  createMarker_map({ map: map, place });
		$config['placesAutocompleteOnChange'] 	= 'var place = placesAutocomplete.getPlace().geometry.location; map.panTo(place); marker_0.setPosition(place); document.getElementById("latitude").value = place.lat(); document.getElementById("longitude").value = place.lng()';

	    
    	$marker = array();
		$marker['position'] 	= $listing->latitude.', '.$listing->longitude;
		$marker['animation'] 	= 'DROP';
		$marker['draggable'] 	= true;
		$marker['ondragend'] 	= 'document.getElementById("latitude").value = event.latLng.lat(); document.getElementById("longitude").value = event.latLng.lng()';
		Gmaps::add_marker($marker);

	    Gmaps::initialize($config);

	    // set up the marker ready for positioning
	    // once we know the users location
	    $marker = array();
	    Gmaps::add_marker($marker);

	    $map = Gmaps::create_map();

		return view('admin.listings.edit', ['listing' 			=> $listing ,
											'categories' 		=> $categories, 
											'listingTypes' 		=> $listingTypes, 
											'cities' 			=> $cities,
											'features' 			=> $features, 
											'map' 				=> $map]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request){
		//
		$listing = Listing::find($id);

		$input 				= $request->all();
		$input['price'] 	= preg_replace("/[^0-9]/", "", $input['price']);
		$input['rooms'] 	= preg_replace("/[^0-9]/", "", $input['rooms']);
		$input['bathrooms'] = preg_replace("/[^0-9]/", "", $input['bathrooms']);
		$input['area'] 		= preg_replace("/[^0-9]/", "", $input['area']);
		$input['lot_area'] 	= preg_replace("/[^0-9]/", "", $input['lot_area']);
		$input['stratum'] 	= preg_replace("/[^0-9]/", "", $input['stratum']);
		$input['garages'] 	= preg_replace("/[^0-9]/", "", $input['garages']);
		$input['floor'] 	= preg_replace("/[^0-9]/", "", $input['floor']);
		$input['construction_year'] 	= preg_replace("/[^0-9]/", "", $input['construction_year']);
		$input['administration'] 		= preg_replace("/[^0-9]/", "", $input['administration']);
		$input['district'] 		= preg_replace("/[^a-zA-Z0-9ñáéíóú ]+/", "", $input['district']);
		$input['description'] 	= preg_replace("/[^a-zA-Z0-9.,?¿ñáéíóú ]+/", "", $input['description']);
		$input['direction'] 	= preg_replace("/[^a-zA-Z0-9# -]+/", "", $input['direction']);

		if($input['main_image_id'] == ""){
			$input['main_image_id'] = null;
		}

		if($input['image_path'] == ""){
			if(count($listing->images) > 0){
				$input['image_path'] = $listing->images->first()->image_path;
			}
		}

		if (!$listing->validate($input, null, true)){
	        return redirect('admin/listings/'.$id.'/edit')->withErrors($listing->errors())->withInput();
	    }

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					Session::flash('errors', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
				}
				// If nos usign ajax return redirect
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

	    if($listing->latitude != $input['latitude'] || $listing->longitude != $input['longitude'] || !$listing->district){
			$listing->district = Geocoder::reverse($input['latitude'], $input['longitude'])->getCityDistrict();
		}

	    $listing->fill($input);


	    $unwanted_array = [ 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' ];
		$district = strtr( $listing->district, $unwanted_array );

		// Create the title and slug of the listing
		$title 				= ucfirst(strtolower(str_singular($listing->category->name) . 
				 			  ' en ' . //TODO translate
				 			  $listing->listingType->name . 
							  ', ' .
							  $district)) .
							  ' ' .
							  $listing->city->name;
							  
		$listing->title 	= str_limit($title, $limit = 245, $end = '');
		$listing->slug 		= str_limit(str_slug($listing->title.'-'.'15638'.$listing->id, '-'), $limit = 245, $end = '');

		// Set listing features
	    $features = Feature::all();
	    $featuresSelected = [];
	    foreach ($features as $feature) {
	    	if($request->has($feature->id)){
	    		$featuresSelected[] = $feature->id;
	    	}
	    }
	    $listing->features()->sync($featuresSelected);

	    $listing->save();

		return redirect('admin/listings/'.$id.'/edit')->withSuccess([trans('responses.listing_saved')]);
	}

	/**
	 * Show renovation dialog for resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function limitShow(){
		return view('admin.listings.limit_reached');
	}

	/**
	 * Show renovation dialog for resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function renovateShow($id, Request $request){
		$listing = Listing::find($id);
		$featuredTypes = FeaturedType::remember(Settings::get('query_cache_time'))->get();

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
	    			Session::flash('errors', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		return view('admin.listings.renovate', ['listing' 		=> $listing,
												'featuredTypes' => $featuredTypes]);
	}

	/**
	 * Renovate the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function renovate($id, Request $request){
		$listing = Listing::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
	    			Session::flash('errors', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Only renovate if is expiring in less than 5 days
		if($listing->expires_at > Carbon::now()->addDays(5)){
			return redirect('admin/listings/')->withErrors([trans('responses.listing_renovation_error')]);
		}

		$listing->expires_at 		= Carbon::now()->addDays(Settings::get('listing_expiring'));
		$listing->expire_notified 	= false;

		$listing->save();

		if($request->ajax()){// If request was sent using ajax
			Session::flash('errors', [trans('responses.listing_renovated')]);
			return response()->json(['success' => trans('responses.listing_renovated')]);
		}
		return redirect('admin/listings/')->withSuccess([trans('responses.listing_renovated')]);
	}

	/**
	 * Recover softdeleted resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function recover($id, Request $request){
		$listing = Listing::withTrashed()->where('id', $id)->first();

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
	    			Session::flash('errors', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Max free listings limit
		if(!Auth::user()->confirmed && Auth::user()->freeListingCount > 0){
			return redirect('admin/not_confirmed');
		}else if(Auth::user()->confirmed && Auth::user()->freeListingCount >= Settings::get('free_listings_limit', 10)){
			return redirect('admin/listings/limit');
		}

		$listing->restore();

		return redirect('admin/listings')->withSuccess([trans('responses.listing_recovered')]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request){
		$listing = Listing::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					Session::flash('errors', [trans('responses.no_permission')]);
					return response()->json(['error' => trans('responses.no_permission')]);
				}
				// If nos usign ajax return redirect
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Delete listing
		$listing->delete();


		if($request->ajax()){// If request was sent using ajax
			Session::flash('success', [trans('responses.listing_deleted')]);
			return response()->json(['error' => trans('responses.listing_deleted')]);
		}
		// If nos usign ajax return redirect
		return redirect('admin/listings')->withErrors([trans('responses.listing_deleted')]);
	}

}
