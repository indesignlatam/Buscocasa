<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Gmaps, Image, DB, Cookie;

use App\Models\Listing, App\Models\Feature, App\Models\City, App\Models\Category;

class SaleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		$listings;
		$listingType = 'Venta';

		$hideMap 	= $request->cookie('hide_map');
		$showMosaic = $request->cookie('show_mosaic');

		if(count($request->all())){
			if($request->get('listing_code')){
				$code 			= $request->get('listing_code');
				$listings 		= Listing::active()->orderBy('id', 'DESC')->paginate(20);//where('listing_code', '=', $code)
			}else{
				$query			= Listing::where('listing_type', '=', 1)->active();
				if($request->get('category_id')){
					$category_id 	= $request->get('category_id');
					$query 			= $query->where('category_id', $category_id);
				}

				if($request->get('city_id')){
					$city_id 		= $request->get('city_id');
					$query 			= $query->where('city_id', $city_id);
				}

				if($request->get('price_min') && $request->get('price_max')){
					$query 			= $query->WhereBetween('price', [$request->get('price_min'), $request->get('price_max')]);
				}

				if($request->get('rooms_min') && $request->get('rooms_max')){
					$query 			= $query->WhereBetween('rooms', [$request->get('rooms_min'), $request->get('rooms_max')]);
				}

				if($request->get('area_min') && $request->get('area_max')){
					$query 			= $query->WhereBetween('area', [$request->get('area_min'), $request->get('area_max')]);
				}

				$listings 	= $query->with('featuredType')->orderBy('id', 'DESC')->paginate(20);
			}
		}else{
			$listings 	= Listing::where('listing_type', '=', 1)->with('featuredType')->active()->orderBy('id', 'DESC')->paginate(20);
		}

		$categories = Category::remember(60)->get();
		$cities 	= City::orderBy('name')->remember(60)->get();

		//Prices
		$minPrice 	= DB::table('listings')->remember(60)->min('price');
		$maxPrice 	= DB::table('listings')->remember(60)->max('price');
		//Area
		$maxArea 	= DB::table('listings')->remember(60)->max('area');


		// MAPS
		$config = array();
	    
	    if(count($listings) > 1){
			$config['zoom'] 	= 'auto';
		}else if(count($listings) != 0){
			$config['center'] 	= $listings->first()->latitude.', '.$listings->first()->longitude;
			$config['zoom'] 	= '15';
		}
		$config['scrollwheel'] 	= false;
	    
	    foreach ($listings as $listing) {
	    	$marker = array();
			$marker['position'] 			= $listing->latitude.', '.$listing->longitude;
			$marker['icon'] 				= asset('/images/maps/marker_icon.png');
			$marker['icon_scaledSize']		= '50,30';
			$marker['infowindow_content'] 	= '<a href="'.url('/ventas/'.$listing->slug).'" style="text-decoration:none"><h3 class="uk-margin-small-bottom">'. $listing->title .'</h3></a><div class="uk-grid" style="width:500px"><div class="uk-width-1-2"><a href="'.url('/ventas/'.$listing->slug).'" style="text-decoration:none"><img src="'. asset(Image::url($listing->image_path(),['map_mini'])) .'" style="width:250px; height:200px"></a></div><div class="uk-width-1-2"><h3 class="uk-margin-left uk-margin-top-remove uk-text-primary">'. money_format('$%!.0i', $listing->price) .'</h3><ul class="uk-list uk-list-line uk-margin-left uk-width-1-1" style="margin-top:-px"><li>'.$listing->rooms.' '.trans("frontend.rooms").'</li><li>'.$listing->bathrooms.' '.trans("frontend.bathrooms").'</li><li>'.$listing->area.' mt2</li><li>'.$listing->garages.' '.trans("frontend.garages").'</li></ul><a href="'.url('/ventas/'.$listing->slug).'" class="uk-button uk-button-primary uk-margin-left">'.trans("frontend.goto_listing").'</a></div></div>';
			$marker['animation'] 			= 'DROP';
			Gmaps::add_marker($marker);
	    }

	    // Only markers in the view port
	    // $config['onidle'] = 'getMarkers();';

		Gmaps::initialize($config);
	    $map = Gmaps::create_map();

		return view('listings.index', [ 'listings' 		=> $listings,
										'listingType' 	=> $listingType,
										'cities' 		=> $cities, 
										'categories' 	=> $categories ,
										'map' 			=> $map,
										'minPrice' 		=> $minPrice,
										'maxPrice' 		=> $maxPrice,
										'maxArea' 		=> $maxArea,
										'hideMap' 		=> $hideMap,
										'showMosaic' 	=> $showMosaic,
										]);
	}

	/**
	 * Get listings on bounds
	 *
	 * @return Response
	 */
	public function markersOnBounds(Request $request){
		//
		$north 	= $request->get('north');
		$east 	= $request->get('east');
		$south 	= $request->get('south');
		$west 	= $request->get('west');

		$listings = Listing::active()
						   ->where('latitude', '>=', $south)
						   ->where('latitude', '<=', $north)
						   ->where('longitude', '>=', $west)
						   ->where('latitude', '<=', $east)
						   ->get();

		return response()->json(['listings' => $listing]);

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
	public function show($id){
		//
		$listingType = 'Venta';
		$listing;
		if(is_string($id)){
			$listing 	= Listing::where('slug', '=', $id)->first();
		}

		if(!$listing){
			return redirect('/');
		}
		
		$features = Feature::all();

		$config = array();
	    $config['center'] 		= $listing->latitude.', '.$listing->longitude;
	    $config['zoom'] 		= '15';
	    $config['scrollwheel'] 	= false;
	    
    	$marker = array();
		$marker['position'] 			= $listing->latitude.', '.$listing->longitude;
		$marker['icon'] 				= url('/images/maps/marker_icon.png');
		$marker['icon_scaledSize']		= '50,27';
		//$marker['infowindow_content'] 	= '<a href="/ventas/'.$listing->slug.'" style="text-decoration:none"><h3 class="uk-margin-small-bottom">'. $listing->title .'</h3></a><div class="uk-grid" style="width:500px"><div class="uk-width-1-2"><a href="/ventas/'.$listing->garages.'" style="text-decoration:none"><img src="'. Image::url($listing->image_path,250,200,array('crop')) .'"></a></div><div class="uk-width-1-2"><h3 class="uk-margin-left uk-margin-top-remove uk-text-primary">'. money_format('$%!.0i', $listing->price) .'</h3><ul class="uk-list uk-list-line uk-margin-left uk-width-1-1" style="margin-top:-px"><li>'.$listing->rooms.' Alcobas</li><li>'.$listing->bathrooms.' Baños</li><li>'.$listing->area.' mts</li><li>'.$listing->garages.' Parqueaderos</li></ul><a href="/ventas/'.$listing->garages.'" class="uk-button uk-button-primary uk-margin-left">Ver inmueble</a></div></div>';
		$marker['animation'] 			= 'DROP';
		Gmaps::add_marker($marker);
		
		Gmaps::initialize($config);
	    $map = Gmaps::create_map();

		return view('listings.show', [	'listing' 		=> $listing,
										'listingType' 	=> $listingType,
										'features' 		=> $features,
										'map' 			=> $map]);
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
