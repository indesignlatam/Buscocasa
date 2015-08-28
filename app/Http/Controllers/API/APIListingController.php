<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Gmaps;
use Image;
use Settings;
use App\Models\Listing;

use	App\Events\ListingViewed;

class APIListingController extends Controller {

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
		$query 		= Listing::remember(Settings::get('query_cache_time_extra_short'))->active();
		$take 		= Settings::get('pagination_objects');
		$listings 	= [];

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
						$query = $query->orderBy('price', 'ASC');
					}else if($request->get('order_by') == 'price_max'){
						$query = $query->orderBy('price', 'DESC');
					}else if($request->get('order_by') == 'id_desc'){
						$query = $query->orderBy('id', 'DESC');
					}else if($request->get('order_by') == 'id_asc'){
						$query = $query->orderBy('id', 'ASC');
					}else if($request->get('order_by') == '0'){
						$query = $query->orderBy('featured_expires_at', 'DESC')->orderBy('featured_type', 'DESC');
					}
				}else{
					$query = $query->orderBy('featured_expires_at', 'DESC')->orderBy('featured_type', 'DESC');
				}

				// Order the query by params
				if($request->has('take')){
					$take = $request->take;
				}
			}// If user didnt input listing code
		}// Has params end


		if(!$request->has('listing_code')){
			$listings = $query->orderBy('id', 'DESC')->with('listingType', 'featuredType')->paginate($take);
		}


		return response()->json($listings);
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

		return response()->json(['listings' => $query,
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
		$listing = Listing::find($id);

		if(!$listing){
			abort(404);
		}

		// Next, we will fire off an event and pass along 
	    event(new ListingViewed($listing));
		
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

		return response()->json(['listing' 	=> $listing,
							     'related' 	=> $related,
							     'compare'	=> $compare,
								]);
	}
}