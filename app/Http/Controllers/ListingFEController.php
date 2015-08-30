<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Gmaps;
use Image;
use Cookie;
use	Settings;
use	Carbon;
use DB;
use Agent;

use App\Models\Listing;
use	App\Models\ListingType;
use	App\Models\Feature;
use	App\Models\City;
use	App\Models\Category;
use	App\Events\ListingViewed;

class ListingFEController extends Controller {

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(){
        $this->middleware('listings.view.throttle', ['only' => ['show']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$mobile 		= Agent::isMobile();
		$query 			= Listing::remember(Settings::get('query_cache_time_extra_short'))->active();
		$listingType 	= 'Venta';
		$listingTypeID 	= 1;
		$listingTypes	= null;
		$take 			= Settings::get('pagination_objects');

		// What are we looking for
		if($request->is('ventas')){
			$listingType 	= 'venta';
			$listingTypeID 	= 1;
			$query->where('listing_type', 1);
		}else if($request->is('arriendos')){
			$listingType 	= 'arriendo';
			$listingTypeID 	= 2;
			$query->where('listing_type', 2);
		}else if($request->is('buscar')){
			$listingType 	= 'Buscar';
			$listingTypeID 	= $request->get('listing_type_id');
			$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		}

		// If there are search params set
		if(count($request->all()) > 0){
			// If user knows the listing code
			if($request->has('listing_code')){
				$listings = $query->where('code', $request->get('listing_code'))
								  ->with('listingType', 'featuredType')
								  ->paginate($take);
			}else{// If user didnt input listing code

				// If user input listing type - Venta o arriendo...
				if($request->has('listing_type_id')){
					$query = $query->where('listing_type', $request->get('listing_type_id'));
				}

				// If user input category_id - casas, apartamentos...
				if($request->has('category_id')){
					$query = $query->where('category_id', $request->get('category_id'));
				}

				// If user input city_id
				if($request->has('city_id')){
					$query = $query->where('city_id', $request->get('city_id'));
				}

				// If user input price_min & price_max
				if($request->has('price_min') && $request->has('price_max')){
					// If user set price_max at the input max dont limit max price
					if($request->get('price_max') >= 2000000000){
						$query = $query->where('price', '>=', $request->get('price_min'));
					}else{// Else limit by min and max price
						$query = $query->WhereBetween('price', [$request->get('price_min')-1, $request->get('price_max')]);
					}
				}

				// If user input rooms_min & rooms_max
				if($request->has('rooms_min') && $request->has('rooms_max')){
					// If user set rooms_max at the input max dont limit max rooms
					if($request->get('rooms_max') >= 10){
						$query = $query->where('rooms', '>=', $request->get('rooms_min'));
					}else{// Else limit by min and max rooms
						$query = $query->WhereBetween('rooms', [$request->get('rooms_min'), $request->get('rooms_max')]);
					}
				}

				// If user input area_min & area_max
				if($request->has('area_min') && $request->has('area_max')){
					// If user set area_max at the input max dont limit max area
					if($request->get('area_max') >= 500){
						$query = $query->where('area', '>=', $request->get('area_min'));
					}else{// Else limit by min and max area
						$query = $query->WhereBetween('area', [$request->get('area_min'), $request->get('area_max')]);
					}
				}

				// If user input lot_area_min & lot_area_max
				if($request->has('lot_area_min') && $request->has('lot_area_max')){
					// If user set area_max at the input max dont limit max lot area
					if($request->get('lot_area_max') >= 2000){
						$query = $query->where('lot_area', '>=', $request->get('lot_area_min'));
					}else{// Else limit by min and max lot area
						$query = $query->WhereBetween('lot_area', [$request->get('lot_area_min'), $request->get('lot_area_max')]);
					}
				}

				// If user input garages_min & garages_max
				if($request->has('garages_min') && $request->has('garages_max')){
					// If user set garages_max at the input max dont limit max garages
					if($request->get('garages_max') >= 5){
						$query = $query->where('garages', '>=', $request->get('garages_min'));
					}else{// Else limit by min and max garages
						$query = $query->WhereBetween('garages', [$request->get('garages_min'), $request->get('garages_max')]);
					}
				}

				// If user input stratum_min & stratum_max
				if($request->has('stratum_min') && $request->has('stratum_max')){
					$query = $query->WhereBetween('stratum', [$request->get('stratum_min'), $request->get('stratum_max')]);
				}

				// Order the query by params
				if($request->has('order_by')){
					if($request->get('order_by') == 'price_min'){
						session(['listings_order_by' => 'price_min']);
						$query = $query->orderBy('price', 'ASC');
					}else if($request->get('order_by') == 'price_max'){
						session(['listings_order_by' => 'price_max']);
						$query = $query->orderBy('price', 'DESC');
					}else if($request->get('order_by') == 'id_desc'){
						session(['listings_order_by' => 'id_desc']);
						$query = $query->orderBy('id', 'DESC');
					}else if($request->get('order_by') == 'id_asc'){
						session(['listings_order_by' => 'id_asc']);
						$query = $query->orderBy('id', 'ASC');
					}else if($request->get('order_by') == '0'){
						session()->forget('listings_order_by');
						$query = $query->orderBy('featured_type', 'DESC');
					}
				}

				// Take n objects
				if($request->has('take')){
					if($request->get('take')){
						$request->session()->put('listings_take', $request->get('take'));
						$take = $request->get('take');
					}
				}
			}// If user didnt input listing code
		}// Has params end

		// Order the query by cookie
		if(!$request->has('order_by') && $request->session()->has('listings_order_by')){
			if(session('listings_order_by') == 'price_min'){
					$query = $query->orderBy('price', 'ASC');
				}else if(session('listings_order_by') == 'price_max'){
					$query = $query->orderBy('price', 'DESC');
				}else if(session('listings_order_by') == 'id_desc'){
					$query = $query->orderBy('id', 'DESC');
				}else if(session('listings_order_by') == 'id_asc'){
					$query = $query->orderBy('id', 'ASC');
				}
		}else{
			$query = $query->orderBy('featured_expires_at', 'DESC')->orderBy('featured_type', 'DESC');
		}

		// Take n objects by cookie
		if(!$request->has('take') && $request->session()->has('listings_take')){
			if($request->session()->get('listings_take')){
				$take = $request->session()->get('listings_take');
			}
		}

		if(!$request->has('listing_code')){
			$listings = $query->orderBy('id', 'DESC')->with('listingType', 'featuredType')->paginate($take);
		}


		//
		$categories = Category::remember(Settings::get('query_cache_time'))->get();
		$cities = City::selectRaw('id, name AS text')->remember(Settings::get('query_cache_time'))->orderBy('ordering')->get();

		// Only if not mobile
		$featuredTop = null;
		$map 		= null;
		$view 		= 'listings.index';

		if(!$mobile){
			// Featured Listings Top
			$fTopQuery 	= null;
			$fTopQuery = Listing::remember(Settings::get('query_cache_time_extra_short', 1))->where('featured_expires_at', '>', DB::raw('now()'));
			if($listingTypeID){
				$fTopQuery = $fTopQuery->where('listing_type', $listingTypeID);
			}
			$featuredTop = $fTopQuery->take(8)
								  	 ->orderByRaw("RAND()")
								  	 ->with('listingType', 'featuredType')
								  	 ->get();
			// Featured Listings Top End

			// MAPS
			$listingsCount = count($listings);
			
		  	if($listingsCount > 0){
		  		$config = [];
			    if($listingsCount > 1){
					$config['zoom'] = 'auto';
				}else if($listingsCount == 1){
					$config['center'] = $listings->first()->latitude.','.$listings->first()->longitude;
					$config['zoom'] = '15';
				}
				$config['scrollwheel'] = false;
			    
			    foreach ($listings as $listing) {
			    	$marker = [];
					$marker['position'] = $listing->latitude.','.$listing->longitude;
					if($listing->featuredType && $listing->featured_expires_at > Carbon::now()){
						$marker['icon'] 				= asset('/images/maps/marker_icon.png');
						$marker['icon_scaledSize']		= '50,30';
						$marker['infowindow_content'] 	= '<a href="'.url($listing->path()).'" style="text-decoration:none"><h3 class="uk-margin-small-bottom">'. $listing->title .'</h3></a><div class="uk-grid" style="width:500px"><div class="uk-width-1-2"><a href="'.url($listing->path()).'" style="text-decoration:none"><img src="'.asset($listing->featuredType->image_path).'" style="position:absolute; top:30; left:0; max-width:100px"><img src="'. asset(Image::url($listing->image_path(),['map_mini'])) .'" style="width:250px; height:200px"></a></div><div class="uk-width-1-2"><h3 class="uk-margin-left uk-margin-top-remove uk-text-primary">'. money_format('$%!.0i', $listing->price) .'</h3><ul class="uk-list uk-list-line uk-margin-left uk-width-1-1" style="margin-top:-px"><li>'.$listing->rooms.' '.trans("frontend.rooms").'</li><li>'.$listing->bathrooms.' '.trans("frontend.bathrooms").'</li><li>'.$listing->area.' mt2</li><li>'.$listing->garages.' '.trans("frontend.garages").'</li></ul><a href="'.url($listing->path()).'" class="uk-button uk-button-primary uk-margin-left">'.trans("frontend.goto_listing").'</a></div></div>';
					}else{
						$marker['icon'] 				= asset('/images/maps/marker_icon.png');
						$marker['icon_scaledSize']		= '50,30';
						$marker['infowindow_content'] 	= '<a href="'.url($listing->path()).'" style="text-decoration:none"><h3 class="uk-margin-small-bottom">'. $listing->title .'</h3></a><div class="uk-grid" style="width:500px"><div class="uk-width-1-2"><a href="'.url($listing->path()).'" style="text-decoration:none"><img src="'. asset(Image::url($listing->image_path(),['map_mini'])) .'" style="width:250px; height:200px"></a></div><div class="uk-width-1-2"><h3 class="uk-margin-left uk-margin-top-remove uk-text-primary">'. money_format('$%!.0i', $listing->price) .'</h3><ul class="uk-list uk-list-line uk-margin-left uk-width-1-1" style="margin-top:-px"><li>'.$listing->rooms.' '.trans("frontend.rooms").'</li><li>'.$listing->bathrooms.' '.trans("frontend.bathrooms").'</li><li>'.$listing->area.' mt2</li><li>'.$listing->garages.' '.trans("frontend.garages").'</li></ul><a href="'.url($listing->path()).'" class="uk-button uk-button-primary uk-margin-left">'.trans("frontend.goto_listing").'</a></div></div>';
					}
					$marker['animation'] = 'DROP';
					Gmaps::add_marker($marker);
			    }

			    Gmaps::initialize($config);
		    	$map = Gmaps::create_map();
	  		}
	  	}else{ // If is mobile
	  		// Set the view to mobile view
	  		$view = 'listings.mobile.index';
	  	}

		return view($view, ['listings' 			=> $listings,
							'featuredListings' 	=> $featuredTop,
							'listingType' 		=> $listingType,
							'listingTypes' 		=> $listingTypes,
							'cities' 			=> $cities, 
							'categories' 		=> $categories ,
							'map'				=> $map,
							]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function indexBounds(Request $request){
		//
		$query = Listing::active();

		// If there are search params set
		if(count($request->all()) > 0){
			// If user knows the listing code
			if($request->has('listing_code')){
				$query = $query ->where('code', $request->get('listing_code'))
								->with('listingType', 'featuredType')
								->get();
			}else{// If user didnt input listing code

				// If user input listing type - Venta o arriendo...
				if($request->has('listing_type_id')){
					$query = $query->where('listing_type', $request->get('listing_type_id'));
				}

				// If user input category_id - casas, apartamentos...
				if($request->has('category_id')){
					$query = $query->where('category_id', $request->get('category_id'));
				}

				// If user input city_id
				if($request->has('city_id')){
					$query = $query->where('city_id', $request->get('city_id'));
				}

				// If user input price_min & price_max
				if($request->has('price_min') && $request->has('price_max')){
					// If user set price_max at the input max dont limit max price
					if($request->get('price_max') >= 2000000000){
						$query = $query->where('price', '>=', $request->get('price_min'));
					}else{// Else limit by min and max price
						$query = $query->WhereBetween('price', [$request->get('price_min')-1, $request->get('price_max')]);
					}
				}

				// If user input rooms_min & rooms_max
				if($request->has('rooms_min') && $request->has('rooms_max')){
					// If user set rooms_max at the input max dont limit max rooms
					if($request->get('rooms_max') >= 10){
						$query = $query->where('rooms', '>=', $request->get('rooms_min'));
					}else{// Else limit by min and max rooms
						$query = $query->WhereBetween('rooms', [$request->get('rooms_min'), $request->get('rooms_max')]);
					}
				}

				// If user input area_min & area_max
				if($request->has('area_min') && $request->has('area_max')){
					// If user set area_max at the input max dont limit max area
					if($request->get('area_max') >= 500){
						$query = $query->where('area', '>=', $request->get('area_min'));
					}else{// Else limit by min and max area
						$query = $query->WhereBetween('area', [$request->get('area_min'), $request->get('area_max')]);
					}
				}

				// If user input lot_area_min & lot_area_max
				if($request->has('lot_area_min') && $request->has('lot_area_max')){
					// If user set area_max at the input max dont limit max lot area
					if($request->get('lot_area_max') >= 2000){
						$query = $query->where('lot_area', '>=', $request->get('lot_area_min'));
					}else{// Else limit by min and max lot area
						$query = $query->WhereBetween('lot_area', [$request->get('lot_area_min'), $request->get('lot_area_max')]);
					}
				}

				// If user input garages_min & garages_max
				if($request->has('garages_min') && $request->has('garages_max')){
					// If user set garages_max at the input max dont limit max garages
					if($request->get('garages_max') >= 5){
						$query = $query->where('garages', '>=', $request->get('garages_min'));
					}else{// Else limit by min and max garages
						$query = $query->WhereBetween('garages', [$request->get('garages_min'), $request->get('garages_max')]);
					}
				}

				// If user input stratum_min & stratum_max
				if($request->has('stratum_min') && $request->has('stratum_max')){
					$query = $query->WhereBetween('stratum', [$request->get('stratum_min'), $request->get('stratum_max')]);
				}

				if($request->has('latitude_a') && $request->has('latitude_b') && $request->has('longitude_a') && $request->has('longitude_b')){
					$query = $query ->where('latitude', '<', $request->get('latitude_a'))
									->where('latitude', '>', $request->get('latitude_b'))
									->where('longitude', '>', $request->get('longitude_a'))
									->where('longitude', '<', $request->get('longitude_b'));
				}

			}// If user didnt input listing code
		}// Has params end
		// lat > a AND lat < c AND lng > b AND lng < d
		if(!$request->has('listing_code')){
			$query = $query ->orderBy('id', 'DESC')
							->with('listingType', 'featuredType')
							->get();
		}

		return response()->json($query);
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
		$listing = Listing::where('slug', $id)->first();

		if(!$listing){
			abort(404);
		}

		// Next, we will fire off an event and pass along 
	    event(new ListingViewed($listing));
		
		$features 	= Feature::remember(Settings::get('query_cache_time'))->with('category')->get();

		$related 	= Listing::remember(Settings::get('query_cache_time_short'))
							 ->selectRaw("*,
                              ( 6371 * acos( cos( radians(?) ) *
                                cos( radians( latitude ) )
                                * cos( radians( longitude ) - radians(?)
                                ) + sin( radians(?) ) *
                                sin( radians( latitude ) ) )
                              ) AS distance")
							 ->setBindings([$listing->latitude, $listing->longitude, $listing->latitude])
							 ->where('id', '<>', $listing->id)
							 ->where('category_id', $listing->category_id)
							 ->where('listing_type', $listing->listing_type)
							 ->with('listingType')
							 ->orderBy('distance')
							 ->take(5)
							 ->get();

		$compare 	= Listing::remember(Settings::get('query_cache_time_short'))
							 ->selectRaw("*,
                              ( 6371 * acos( cos( radians(?) ) *
                                cos( radians( latitude ) )
                                * cos( radians( longitude ) - radians(?)
                                ) + sin( radians(?) ) *
                                sin( radians( latitude ) ) )
                              ) AS distance")
							 ->setBindings([$listing->latitude, $listing->longitude, $listing->latitude])
							 ->where('id', '<>', $listing->id)
							 ->where('city_id', $listing->city_id)
							 ->where('category_id', $listing->category_id)
							 ->where('listing_type', $listing->listing_type)
							 ->having('distance', '<', 1)
							 ->orderBy('distance')
							 ->orderBy('stratum')
							 ->orderBy('construction_year')
							 ->with('listingType')
							 ->take(10)
							 ->get();

		return view('listings.show', [ 'listing' 	=> $listing,
									   'related' 	=> $related,
									   'features' 	=> $features,
									   'compare'	=> $compare,
									]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function compare(Request $request){
		$listingsIds = Cookie::get('selected_listings');
		$listings = null;

		if(count($listingsIds) > 1){
			$listings = Listing::whereIn('id', $listingsIds)->take(4)->with('features', 'listingType')->get();
		}else if($listingsIds){
			$listings = Listing::where('id', $listingsIds)->take(4)->with('features', 'listingType')->get();
		}

		$features = Feature::remember(Settings::get('query_cache_time'))->with('category')->get();

		return view('listings.compare', ['listings' => $listings,
										 'features' => $features
										 ]);
	}
}