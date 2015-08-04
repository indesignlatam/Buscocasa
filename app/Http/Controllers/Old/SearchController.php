<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB, Gmaps, Image;

use App\Models\Listing, App\Models\ListingType, App\Models\City, App\Models\Category; 

class SearchController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
		$listings;
		$listingType = 'Buscar';

		$hideMap 	= $request->cookie('hide_map');
		$showMosaic = $request->cookie('show_mosaic');

		if(count($request->all())){
			if($request->get('listing_code')){
				$code 			= $request->get('listing_code');
				$listings 		= Listing::where('listing_code', '=', $code)->orderBy('id', 'DESC')->paginate(30);
			}else{
				$query			= Listing::where('listing_type', '=', 1);
				if($request->get('category_id')){
					$category_id 	= $request->get('category_id');
					$query 			= $query->where('category_id', $category_id);
				}

				if($request->has('listing_type_id')){
					$listing_type_id 	= $request->get('listing_type_id');
					$query 				= $query->where('listing_type', $listing_type_id);
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

				$listings 	= $query->paginate(30);
			}
		}else{
			$listings 	= Listing::where('listing_type', '=', 1)->orderBy('id', 'DESC')->paginate(30);
		}

		$categories 	= Category::all();
		$cities 		= City::all();
		$listingTypes 	= ListingType::all();


		//Prices
		$minPrice 	= DB::table('listings')->min('price');
		$maxPrice 	= DB::table('listings')->max('price');
		//Area
		$maxArea 	= DB::table('listings')->max('area');


		// MAPS
		$config = array();
	    
	    if(count($listings) > 1){
			$config['zoom'] 	= 'auto';
		}else if(count($listings) != 0){
			$config['center'] 	= $listings->first()->latitude.', '.$listings->first()->longitude;
			$config['zoom'] 	= '15';
		}
		$config['scrollwheel'] 	= false;
	    
	    setlocale(LC_MONETARY, 'es_ES');
	    foreach ($listings as $listing) {
	    	$marker = array();
			$marker['position'] 			= $listing->latitude.', '.$listing->longitude;
			$marker['icon'] 				= asset('/images/maps/marker_icon.png');
			$marker['icon_scaledSize']		= '50,27';
			$marker['infowindow_content'] 	= '<a href="'.url(strtolower('/'.$listing->listingType->name.'s/'.$listing->slug)).'" style="text-decoration:none"><h3 class="uk-margin-small-bottom">'. $listing->title .'</h3></a><div class="uk-grid" style="width:500px"><div class="uk-width-1-2"><a href="'.url(strtolower('/'.$listing->listingType->name.'s/'.$listing->slug)).'" style="text-decoration:none"><img src="'. asset(Image::url($listing->image_path(),['map_mini'])) .'" style="width:250px; height:200px"></a></div><div class="uk-width-1-2"><h3 class="uk-margin-left uk-margin-top-remove uk-text-primary">'. money_format('$%!.0i', $listing->price) .'</h3><ul class="uk-list uk-list-line uk-margin-left uk-width-1-1" style="margin-top:-px"><li>'.$listing->rooms.' '.trans("frontend.rooms").'</li><li>'.$listing->bathrooms.' '.trans("frontend.bathrooms").'</li><li>'.$listing->area.' mt2</li><li>'.$listing->garages.' '.trans("frontend.garages").'</li></ul><a href="'.url(strtolower('/'.$listing->listingType->name.'s/'.$listing->slug)).'" class="uk-button uk-button-primary uk-margin-left">'.trans("frontend.goto_listing").'</a></div></div>';
			$marker['animation'] 			= 'DROP';
			Gmaps::add_marker($marker);
	    }
		

		Gmaps::initialize($config);

	    $map = Gmaps::create_map();

		return view('listings.index', [ 'listings' 		=> $listings,
										'listingType' 	=> $listingType,
										'listingTypes' 	=> $listingTypes,
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
