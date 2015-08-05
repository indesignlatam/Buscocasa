<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Gmaps, 
	Image, 
	DB, 
	Cookie,
	Settings,
	Carbon;

use App\Models\Listing, 
	App\Models\ListingType, 
	App\Models\Feature, 
	App\Models\City, 
	App\Models\Category;
use	App\Events\ListingViewed;

class ListingFEController extends Controller {

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(){
		//
        $this->middleware('listings.view.throttle', ['only' => ['show']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		$listings;
		$listingType 	= 'Venta';
		$listingTypes 	= null;
		$listingTypeID 	= 1;
		$take 			= Settings::get('pagination_objects');

		$query			= Listing::remember(Settings::get('query_cache_time_short', 10))->active();

		if($request->is('ventas')){
			$listingType 	= 'Venta';
			$listingTypeID 	= 1;
			$query->where('listing_type', 1);
		}else if($request->is('arriendos')){
			$listingType 	= 'Arriendo';
			$listingTypeID 	= 2;
			$query->where('listing_type', 2);
		}else if($request->is('buscar')){
			$listingType 	= 'Buscar';
			$listingTypeID 	= $request->get('listing_type_id');
			$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		}

		if($request->has('take') && is_int($request->get('take'))){
			$take = $request->get('take');
		}


		if(count($request->all()) > 0){
			if($request->get('listing_code')){
				$code 			= $request->get('listing_code');
				$listings 		= Listing::remember(Settings::get('query_cache_time_short', 10))
										->where('code', $request->get('listing_code'))
										->active()
										->orderBy('featured_type', 'DESC')
										->orderBy('id', 'DESC')
										->with('city', 'listingType', 'featuredType')
										->paginate($take);
			}else{
				if($request->get('listing_type_id')){
					$listing_type 	= $request->get('listing_type_id');
					$query 			= $query->where('listing_type', $listing_type);
				}

				if($request->get('category_id')){
					$category_id 	= $request->get('category_id');
					$query 			= $query->where('category_id', $category_id);
				}

				if($request->get('city_id')){
					$city_id 		= $request->get('city_id');
					$query 			= $query->where('city_id', $city_id);
				}

				if($request->get('price_min') && $request->get('price_max')){
					$priceMin = $request->get('price_min');
					$priceMax = $request->get('price_max');

					if($priceMax >= 2000000000){// TODO insert to settings
						$query = $query->where('price', '>=', $priceMin);
					}else{
						$query = $query->WhereBetween('price', [$priceMin-1, $priceMax]);
					}
				}

				if($request->get('rooms_min') && $request->get('rooms_max')){
					$roomMin = $request->get('rooms_min');
					$roomMax = $request->get('rooms_max');

					if($roomMax >= 10){// TODO insert to settings
						$query = $query->where('rooms', '>=', $roomMin);
					}else{
						$query = $query->WhereBetween('rooms', [$roomMin, $roomMax]);
					}
				}

				if($request->get('area_min') && $request->get('area_max')){
					$areaMin = $request->get('area_min');
					$areaMax = $request->get('area_max');

					if($areaMax >= 500){// TODO insert to settings
						$query = $query->where('area', '>=', $areaMin);
					}else{
						$query = $query->WhereBetween('area', [$areaMin-1, $areaMax]);
					}
				}

				if($request->get('order_by')){
					// Log::info('Order_by');
					if($request->get('order_by') == 'price_min'){
						Cookie::queue('listings_order_by', 'price_min', 60);// TODO insert to settings
						$query 		= $query->orderBy('price', 'ASC');
					}else if($request->get('order_by') == 'price_max'){
						Cookie::queue('listings_order_by', 'price_max', 60);// TODO insert to settings
						$query 		= $query->orderBy('price', 'DESC');
					}else if($request->get('order_by') == 'id_desc'){
						Cookie::queue('listings_order_by', 'id_desc', 60);// TODO insert to settings
						$query 		= $query->orderBy('id', 'DESC');
					}					
				}else if (Cookie::get('listings_order_by')) {
					// Log::info('Order_by cookie');
					if(Cookie::get('listings_order_by') == 'price_min'){
						$query 		= $query->orderBy('price', 'ASC');
					}else if(Cookie::get('listings_order_by') == 'price_max'){
						$query 		= $query->orderBy('price', 'DESC');
					}else if(Cookie::get('listings_order_by') == 'id_desc'){
						$query 		= $query->orderBy('id', 'DESC');
					}
				}else{
					$query 	= $query->orderBy('featured_type', 'DESC');
				}

				$listings 	= $query->orderBy('id', 'DESC')->with('city', 'listingType', 'featuredType')->paginate($take);
			}
		}else{
			$query 		= $query->with('featuredType');

			if (Cookie::get('listings_order_by')) {
				// Log::info('Order_by cookie');
				if(Cookie::get('listings_order_by') == 'price_min'){
					$query 		= $query->orderBy('price', 'ASC');
				}else if(Cookie::get('listings_order_by') == 'price_max'){
					$query 		= $query->orderBy('price', 'DESC');
				}else if(Cookie::get('listings_order_by') == 'id_desc'){
					$query 		= $query->orderBy('id', 'DESC');
				}
			}else{
				$query 	= $query->orderBy('featured_type', 'DESC');
			}

			$listings 	= $query->orderBy('id', 'DESC')->with('city', 'listingType', 'featuredType')->paginate($take);
		}

		$categories 	= Category::remember(Settings::get('query_cache_time'))->get();
		$cities 		= City::remember(Settings::get('query_cache_time'))->orderBy('ordering')->get();

		// Featured Listings
		if($listingTypeID){
			$featuredListings = Listing::remember(Settings::get('query_cache_time_extra_short', 1))
								   ->where('featured_expires_at', '>', Carbon::now())
								   ->where('listing_type', $listingTypeID)
								   ->take(8)
								   ->orderByRaw("RAND()")
								   ->with('city', 'listingType', 'featuredType')
								   ->remember(Settings::get('query_cache_time_extra_short', 1))
								   ->get();
		}else{
			$featuredListings = Listing::remember(Settings::get('query_cache_time_extra_short', 1))
								   ->where('featured_expires_at', '>', Carbon::now())
								   ->take(8)
								   ->orderByRaw("RAND()")
								   ->with('city', 'listingType', 'featuredType')
								   ->get();
		}
		


		// MAPS
		$config = array();
	    if(count($listings) > 1){
			$config['zoom'] 	= 'auto';
		}else if(count($listings) != 0){
			$config['center'] 	= $listings->first()->latitude.','.$listings->first()->longitude;
			$config['zoom'] 	= '15';
		}
		$config['scrollwheel'] 	= false;
	    
	    foreach ($listings as $listing) {
	    	$marker = array();
			$marker['position'] 			= $listing->latitude.','.$listing->longitude;
			$marker['icon'] 				= asset('/images/maps/marker_icon.png');
			$marker['icon_scaledSize']		= '50,30';
			if($listing->featuredType && $listing->featured_expires_at > Carbon::now()){
				$marker['infowindow_content'] 	= '<a href="'.url($listing->path()).'" style="text-decoration:none"><h3 class="uk-margin-small-bottom">'. $listing->title .'</h3></a><div class="uk-grid" style="width:500px"><div class="uk-width-1-2"><a href="'.url($listing->path()).'" style="text-decoration:none"><img src="'.asset($listing->featuredType->image_path).'" style="position:absolute; top:30; left:0; max-width:100px"><img src="'. asset(Image::url($listing->image_path(),['map_mini'])) .'" style="width:250px; height:200px"></a></div><div class="uk-width-1-2"><h3 class="uk-margin-left uk-margin-top-remove uk-text-primary">'. money_format('$%!.0i', $listing->price) .'</h3><ul class="uk-list uk-list-line uk-margin-left uk-width-1-1" style="margin-top:-px"><li>'.$listing->rooms.' '.trans("frontend.rooms").'</li><li>'.$listing->bathrooms.' '.trans("frontend.bathrooms").'</li><li>'.$listing->area.' mt2</li><li>'.$listing->garages.' '.trans("frontend.garages").'</li></ul><a href="'.url($listing->path()).'" class="uk-button uk-button-primary uk-margin-left">'.trans("frontend.goto_listing").'</a></div></div>';
			}else{
				$marker['infowindow_content'] 	= '<a href="'.url($listing->path()).'" style="text-decoration:none"><h3 class="uk-margin-small-bottom">'. $listing->title .'</h3></a><div class="uk-grid" style="width:500px"><div class="uk-width-1-2"><a href="'.url($listing->path()).'" style="text-decoration:none"><img src="'. asset(Image::url($listing->image_path(),['map_mini'])) .'" style="width:250px; height:200px"></a></div><div class="uk-width-1-2"><h3 class="uk-margin-left uk-margin-top-remove uk-text-primary">'. money_format('$%!.0i', $listing->price) .'</h3><ul class="uk-list uk-list-line uk-margin-left uk-width-1-1" style="margin-top:-px"><li>'.$listing->rooms.' '.trans("frontend.rooms").'</li><li>'.$listing->bathrooms.' '.trans("frontend.bathrooms").'</li><li>'.$listing->area.' mt2</li><li>'.$listing->garages.' '.trans("frontend.garages").'</li></ul><a href="'.url($listing->path()).'" class="uk-button uk-button-primary uk-margin-left">'.trans("frontend.goto_listing").'</a></div></div>';
			}
			$marker['animation'] 			= 'DROP';
			Gmaps::add_marker($marker);
	    }

	    // Only markers in the view port
	    // $config['onidle'] = 'getMarkers();';

		Gmaps::initialize($config);
	    $map = Gmaps::create_map();

		return view('listings.index', [ 'listings' 			=> $listings,
										'featuredListings' 	=> $featuredListings,
										'listingType' 		=> $listingType,
										'listingTypes' 		=> $listingTypes,
										'cities' 			=> $cities, 
										'categories' 		=> $categories ,
										'map' 				=> $map,
										]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
		$listing;
		if(is_string($id)){
			$listing = Listing::remember(Settings::get('query_cache_time_short', 10))->where('slug', $id)->first();
		}

		if(!$listing){
			return redirect('/');
		}

		// Next, we will fire off an event and pass along 
	    event(new ListingViewed($listing));
		
		$features 	= Feature::remember(Settings::get('query_cache_time'))->with('category')->get();

		$related 	= Listing::select(DB::raw("*,
                              ( 6371 * acos( cos( radians(?) ) *
                                cos( radians( latitude ) )
                                * cos( radians( longitude ) - radians(?)
                                ) + sin( radians(?) ) *
                                sin( radians( latitude ) ) )
                              ) AS distance"))
							 ->setBindings([$listing->latitude, $listing->longitude, $listing->latitude])
							 ->where('id', '<>', $listing->id)
							 ->where('category_id', $listing->category_id)
							 ->where('listing_type', $listing->listing_type)
							 ->with('listingType')
							 ->orderBy('distance')
							 ->take(3)
							 ->get();


		$config 	= array();
	    $config['center'] 		= $listing->latitude.','.$listing->longitude;
	    $config['zoom'] 		= '14';
	    $config['scrollwheel'] 	= false;
	    
    	$marker = array();
		$marker['position'] 			= $listing->latitude.','.$listing->longitude;
		$marker['icon'] 				= url('/images/maps/marker_icon.png');
		$marker['icon_scaledSize']		= '50,27';
		$marker['animation'] 			= 'DROP';
		Gmaps::add_marker($marker);
		
		Gmaps::initialize($config);
	    $map = Gmaps::create_map();

		return view('listings.show', [	'listing' 		=> $listing,
										'related' 		=> $related,
										'features' 		=> $features,
										'map' 			=> $map
									]);
	}
}