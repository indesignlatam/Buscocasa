<?php namespace App\Http\Controllers;

use DB, Settings;

use App\Models\Listing, App\Models\City, App\Models\ListingType, App\Models\Category;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

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
										 ->featured()
										 ->with('city', 'listingType', 'featuredType')
										 ->orderBy('featured_type', 'asc')
										 ->orderBy('id', 'desc')
										 ->take(10)
										 ->get();

		$leases 				= Listing::remember(Settings::get('query_cache_time_extra_short'))
										 ->where('listing_type', 2)
										 ->featured()
										 ->with('city', 'listingType', 'featuredType')
										 ->orderBy('featured_type', 'asc')
										 ->orderBy('id', 'desc')
										 ->take(10)
										 ->get();

		$featured 				= Listing::remember(Settings::get('query_cache_time_extra_short'))
										 ->featured()
										 ->orderBy(DB::raw('RAND()'))
										 ->take(2)
										 ->get();

		$featuredFullScreen 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
										 ->where('listing_type', 1)
										 ->where('featured', 1)
										 ->where('price', '>', 1200000000)
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
