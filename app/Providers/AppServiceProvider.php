<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Image;
use App;
use Carbon;
use Validator;
use Request;

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

        // Frontpage or welcome images
        Image::filter('mini_front', [
            'width'     => 256,
            'height'    => 170,
            'crop'      => true,
        ]);
        Image::filter('mini_front_2x', [
            'width'     => 512,
            'height'    => 340,
            'crop'      => true,
        ]);
        Image::filter('featured_mosaic', [
            'width'     => 370,
            'height'    => 230,
            'crop'      => true,
        ]);
        Image::filter('featured_mosaic_2x', [
            'width'     => 740,
            'height'    => 460,
            'crop'      => true,
        ]);
        Image::filter('featured_mosaic_large', [
            'width'     => 750,
            'height'    => 230,
            'crop'      => true,
        ]);
        Image::filter('featured_mosaic_large_2x', [
            'width'     => 1500,
            'height'    => 460,
            'crop'      => true,
        ]);

        //
        Image::filter('full_page', [
            'width'     => 1200,
            'height'    => 350,
            'crop'      => true,
        ]);

        Image::filter('full_image', [
            'width'     => 800,
            'height'    => 400,
            'crop'      => true,
        ]);

        Image::filter('facebook_share', [
            'width'     => 800,
            'height'    => 400,
            'crop'      => true,
        ]);

        Image::filter('mini_image_2x', [
            'width'     => 700,
            'height'    => 400,
            'crop'      => true,
        ]);

        Image::filter('featured_front', [
            'width'     => 1250,
            'height'    => 450,
            'crop'      => true,
        ]);

        Image::filter('map_mini', [
            'width'     => 500,
            'height'    => 400,
            'crop'      => true,
        ]);

        Validator::extend('img_min_size', function($attribute, $value, $parameters){
            $file           = Request::file($attribute);
            $image_info     = getimagesize($file);
            $image_width    = $image_info[0];
            $image_height   = $image_info[1];
            if( (isset($parameters[0]) && $parameters[0] != 0) && $image_width < $parameters[0]) return false;
            if( (isset($parameters[1]) && $parameters[1] != 0) && $image_height < $parameters[1] ) return false;
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        //
    }
}
