<?php namespace App\Http\Controllers;

use DB;
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
		//$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(){
		$sales 					= Listing::remember(Settings::get('query_cache_time_extra_short'))
										 ->where('listing_type', 1)
										 ->where('featured_type', '>', 2)
										 ->with('city', 'listingType', 'featuredType')
										 ->orderBy('featured_type', 'asc')
										 ->orderBy('id', 'desc')
										 ->take(10)
										 ->get();

		$leases 				= Listing::remember(Settings::get('query_cache_time_extra_short'))
										 ->where('listing_type', 2)
										 ->where('featured_type', '>', 2)
										 ->with('city', 'listingType', 'featuredType')
										 ->orderBy('featured_type', 'asc')
										 ->orderBy('id', 'desc')
										 ->take(10)
										 ->get();

		$featured 				= Listing::remember(Settings::get('query_cache_time_extra_short'))
										 ->where('featured_type', '>', 2)
										 ->orderBy(DB::raw('RAND()'))
										 ->take(2)
										 ->get();

		$featuredFullScreen 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
										 ->where('listing_type', 1)
										 ->where('featured', 1)
										 ->orderBy(DB::raw('RAND()'))
										 ->first();

		$cities 				= City::remember(Settings::get('query_cache_time'))->orderBy('ordering')->get();
		$listingTypes 			= ListingType::remember(Settings::get('query_cache_time'))->get();
		$categories 			= Category::remember(Settings::get('query_cache_time'))->get();

		return view('welcome', ['cities' 				=> $cities,
								'categories' 			=> $categories,
								'listingTypes' 			=> $listingTypes,
								'sales' 				=> $sales,
								'leases' 				=> $leases,
								'featured'				=> $featured,
								'featuredFullScreen'	=> $featuredFullScreen,
								]);
	}

}