<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Image, Validator, Request;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(){
		//
		App::setLocale('es');
		Carbon::setLocale('es');
		setlocale(LC_ALL, 'es_ES');
		setlocale(LC_MONETARY, 'en_US');
		date_default_timezone_set('America/Bogota');


		Image::filter('full_page', [
		    'width' 	=> 1200,
		    'height' 	=> 350,
		    'crop' 		=> true,
		]);

		Image::filter('full_image', [
		    'width' 	=> 800,
		    'height' 	=> 400,
		    'crop' 		=> true,
		]);

		Image::filter('facebook_share', [
		    'width' 	=> 800,
		    'height' 	=> 400,
		    'crop' 		=> true,
		]);

		Image::filter('mini_image_2x', [
		    'width' 	=> 700,
		    'height' 	=> 400,
		    'crop' 		=> true,
		]);

		Image::filter('featured_front', [
		    'width' 	=> 1200,
		    'height' 	=> 450,
		    'crop' 		=> true,
		]);

		Image::filter('mini_front', [
		    'width' 	=> 454,
		    'height' 	=> 300,
		    'crop' 		=> true,
		]);

		Image::filter('map_mini', [
		    'width' 	=> 500,
		    'height' 	=> 400,
		    'crop' 		=> true,
		]);
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register(){
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
