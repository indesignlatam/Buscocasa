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

Route::get('/', 'WelcomeController@index');

Route::resource('ventas', 'ListingFEController');
Route::resource('arriendos', 'ListingFEController');
Route::get('compare', 'ListingFEController@compare');
Route::resource('buscar', 'ListingFEController', ['only' => ['index']]);

Route::post('appointments', 'AppointmentController@store');

Route::get('user/{id}/confirm/{code}', 'UserController@confirm');
Route::get('/{username}', 'UserController@show');

Route::post('pagos/confirmar', 'PaymentController@confirm');
Route::post('pagos/disputas', 'PaymentController@dispute');

Route::controllers([
	'auth' 		=> 'Auth\AuthController',
	'password' 	=> 'Auth\PasswordController',
	'cookie' 	=> 'CookieController',
]);
Route::get('social-auth/{provider?}', 'Auth\AuthController@redirectToProvider');
Route::get('social-auth/{provider?}/redirects', 'Auth\AuthController@handleProviderCallback');
