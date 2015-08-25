<?php namespace App\Http\Controllers;

use Settings;

use App\Models\Listing;
use App\Models\City;
use App\Models\ListingType;
use App\Models\Category;

class WelcomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		//
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(){
		$sales 		= Listing::remember(Settings::get('query_cache_time_extra_short'))
							 ->active()
							 ->where('listing_type', 1)
							 ->whereNotNull('image_path')
							 ->where('image_path', '<>', '')
							 ->with('listingType')
							 ->orderBy('featured_type', 'DESC')
							 ->orderBy('featured_expires_at', 'DESC')
							 ->take(12)
							 ->get();

		$leases 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
							 ->active()
							 ->where('listing_type', 2)
							 ->whereNotNull('image_path')
							 ->where('image_path', '<>', '')
							 ->with('listingType')
							 ->orderBy('featured_type', 'DESC')
							 ->orderBy('featured_expires_at', 'DESC')
							 ->take(12)
							 ->get();

		$featured 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
							 ->active()
							 ->where('featured_type', '>', 2)
							 ->whereNotNull('image_path')
							 ->where('image_path', '<>', '')
							 ->with('listingType')
							 ->orderByRaw('RAND()')
							 ->take(8)
							 ->get();

		$cities 		= City::remember(Settings::get('query_cache_time'))->orderBy('ordering')->get();
		$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		$categories 	= Category::remember(Settings::get('query_cache_time'))->get();

		return view('welcome', ['cities' 				=> $cities,
								'categories' 			=> $categories,
								'listingTypes' 			=> $listingTypes,
								'sales' 				=> $sales,
								'leases' 				=> $leases,
								'featured'				=> $featured,
								]);
	}

}