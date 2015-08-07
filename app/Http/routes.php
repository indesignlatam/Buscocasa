<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
App::setLocale('es');
Carbon::setLocale('es');
setlocale(LC_ALL, 'es_ES');
setlocale(LC_MONETARY, 'en_US');
date_default_timezone_set('America/Bogota');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here are all the API routes for external consumption.
|
| 
|
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){
	Route::get('/', 'HomeController@index');

	Route::get('listings/limit', 'ListingController@limitShow');// Secured
	Route::get('listings/{id}/renovate', 'ListingController@renovateShow');// Secured
	Route::post('listings/{id}/renovate', 'ListingController@renovate');// Secured
	Route::get('listings/{id}/recover', 'ListingController@recover');// Secured
	Route::resource('listings', 'ListingController', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);// Secured

	Route::resource('images', 'ImageController', ['only' => ['store', 'destroy']]);// Secured

	Route::post('messages/{id}/answer', 'AppointmentController@answer');// Secured
	Route::post('messages/{id}/mark', 'AppointmentController@markAsRead');// Secured
	Route::resource('messages', 'AppointmentController', ['only' => ['index', 'store', 'show','destroy']]);// Secured

	Route::resource('destacar', 'HighlightController');

	Route::delete('pagos/{id}', 'PaymentController@cancel');// Secured
	Route::get('pagos/respuesta', 'PaymentController@payUResponse');
	Route::resource('pagos', 'PaymentController');

	Route::resource('banners', 'BannerController');

	Route::get('user/send_confirmation_email', 'UserController@sendConfirmationEmail');// Secured
	Route::get('user/not_confirmed', 'UserController@notConfirmed');// Secured
	Route::resource('user', 'UserController', ['only' => ['edit', 'update']]);// Secured
});

/*
|--------------------------------------------------------------------------
| Super user admin Routes
|--------------------------------------------------------------------------
|
| Here are all the API routes for external consumption.
|
| 
|
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function(){
	Route::resource('config', 'SettingsController');
	Route::resource('users', 'UserController');

	Route::resource('categories', 'CategoryController');
	Route::resource('feature-categories', 'FeatureCategoryController');
	Route::resource('listing-types', 'ListingTypeController');
	Route::resource('listing-statuses', 'ListingStatusController');

	Route::resource('features', 'FeatureController');
	Route::resource('cities', 'CityController');

	Route::resource('roles', 'RoleController');
	Route::post('roles/attach', 'RoleController@attachPermission');
	Route::post('roles/delete', 'RoleController@destroyMultiple');
	Route::resource('permissions', 'PermissionController');
	Route::post('permissions/delete', 'PermissionController@destroyMultiple');
});

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Here are all the API routes for external consumption.
|
| 
|
*/
Route::controllers([
	'auth' 		=> 'Auth\AuthController',
	'password' 	=> 'Auth\PasswordController',
]);
Route::get('social-auth/{provider?}', 'Auth\AuthController@redirectToProvider');
Route::get('social-auth/{provider?}/redirects', 'Auth\AuthController@handleProviderCallback');


Route::get('/', 'WelcomeController@index');

Route::resource('ventas', 'ListingFEController');
Route::resource('arriendos', 'ListingFEController');
Route::resource('buscar', 'ListingFEController', ['only' => ['index']]);

Route::post('pagos/confirmar', 'PaymentController@confirm');
Route::post('pagos/disputas', 'PaymentController@dispute');

Route::get('user/{id}/confirm/{code}', 'UserController@confirm');
Route::get('/{username}', 'UserController@show');

Route::post('appointments', 'AppointmentController@store');

Route::resource('nosotros', 'FrontendController@nosotros');
Route::resource('publicar', 'FrontendController@publica');

/*
|--------------------------------------------------------------------------
| Cookie Routes
|--------------------------------------------------------------------------
|
| Here are all the API routes for external consumption.
|
| 
|
*/
Route::group(['prefix' => 'cookie'], function(){
	Route::post('/set', function(){
		if(Input::has('time')){
			Cookie::queue(Input::get('key'), Input::get('value'), Input::get('time'));
		}else{
			Cookie::queue(Input::get('key'), Input::get('value'));
		}
	    return Response::json(['success' => true, 'key' => Input::get('key'), 'value' => Input::get('value')]);
	});
});

/*
|--------------------------------------------------------------------------
| Email preview Routes
|--------------------------------------------------------------------------
|
| Here are all the API routes for external consumption.
|
| 
|
*/
Route::group(['prefix' => 'emails', 'middleware' => 'auth.admin'], function(){
	Route::get('confirm_user', function(){
	    return view('emails.confirm_user', ['user' => Auth::user()]);
	});

	Route::get('listing_expiring', function(){
	    return view('emails.listing_expiring', ['listing' => Auth::user()->listings->first()]);
	});

	Route::get('message_answer', function(){
	    return view('emails.message_answer', ['messageToAnswer' => Auth::user()->appointments->first(), 'comments' => 'maiw miaw miaw miaw maiw']);
	});

	Route::get('message', function(){
	    return view('emails.message', ['userMessage' => Auth::user()->appointments->first()]);
	});

	Route::get('password', function(){
	    return view('emails.password', ['token' => 'miawmiawmiawmiawmiawmiamwidasdasda']);
	});

	Route::get('payment_confirmation', function(){
		$payment = App\Models\Payment::find(7);
	    return view('emails.paymentConfirmation', ['payment' => $payment]);
	});

	Route::get('tips', function(){
	    return view('emails.tips', ['listing' => Auth::user()->listings->first()]);
	});
});