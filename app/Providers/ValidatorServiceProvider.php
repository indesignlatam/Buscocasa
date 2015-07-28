<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Validator, Request;

class ValidatorServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(){
		//
		Validator::extend('img_min_size', function($attribute, $value, $parameters){
            $file 			= Request::file($attribute);
            $image_info 	= getimagesize($file);
            $image_width 	= $image_info[0];
            $image_height 	= $image_info[1];
            if( (isset($parameters[0]) && $parameters[0] != 0) && $image_width < $parameters[0]) return false;
            if( (isset($parameters[1]) && $parameters[1] != 0) && $image_height < $parameters[1] ) return false;
            return true;
    	});
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
		
	}

}
