<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SiteInit extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('categories', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('image_path');
		    $table->boolean('published')->default(true);
		});

		Schema::create('feature_categories', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->boolean('published')->default(true);
		});

		Schema::create('listing_types', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('image_path');
		    $table->boolean('published')->default(true);
		});

		Schema::create('listing_statuses', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('image_path');
		    $table->boolean('published')->default(true);
		});

		Schema::create('features', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->integer('category_id')->unsigned()->nullable();// Broker
		    $table->boolean('published')->default(true);

		    //Foreign Keys - Relationships
	        $table->foreign('category_id')->references('id')->on('feature_categories');
		});

		Schema::create('cities', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->integer('country_id');

		    //Foreign Keys - Relationships
	        $table->foreign('country_id')->references('id')->on('countries');
		});

		Schema::create('listings', function($table){
		    $table->increments('id');

		    $table->integer('broker_id')->unsigned()->nullable();// Broker
		    $table->integer('category_id')->unsigned()->nullable();// Categoria 	= Casas, Apartamentos, Lotes, Fincas
		    $table->integer('listing_type')->unsigned()->nullable();// Tipo 	= Venta, Arriendo
		    $table->integer('listing_status')->unsigned()->nullable();// Estado	= Vendido, Arrendado
		    $table->integer('city_id')->unsigned()->nullable();

		    $table->string('direction');
		    $table->string('latitude');
		    $table->string('longitude');

		    $table->string('title');
		    $table->text('description');
		    $table->float('price');

		   	$table->integer('rooms')->unsigned()->nullable();
		   	$table->integer('bathrooms')->unsigned()->nullable();
		   	$table->integer('garages')->unsigned()->nullable();
		   	$table->integer('area')->unsigned()->nullable();
		   	$table->integer('lot_area')->unsigned()->nullable();
		   	$table->integer('construction_year')->unsigned()->nullable();
		   	$table->float('administration')->unsigned()->nullable();

		    $table->string('image_path');// Imagen principal

		    $table->boolean('published')->default(true);
		    $table->boolean('featured')->default(false);

		    $table->timestamps();
		    $table->softDeletes();

		    //Foreign Keys - Relationships
	        $table->foreign('broker_id')->references('id')->on('users');
	        $table->foreign('category_id')->references('id')->on('categories');
	        $table->foreign('listing_type')->references('id')->on('listing_types');
	        $table->foreign('listing_status')->references('id')->on('listing_statuses');
	        $table->foreign('city_id')->references('id')->on('cities');
		});

		Schema::create('feature_listing', function($table){
		    $table->increments('id');

		    $table->integer('feature_id')->unsigned()->nullable();// Broker
		    $table->integer('listing_id')->unsigned()->nullable();// Broker

		    //Foreign Keys - Relationships
	        $table->foreign('feature_id')->references('id')->on('features');
	        $table->foreign('listing_id')->references('id')->on('listings');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//

		Schema::drop('feature_listing');
		Schema::drop('features');
		Schema::drop('listings');
		Schema::drop('cities');
		Schema::drop('listing_statuses');
		Schema::drop('listing_types');
		Schema::drop('feature_categories');
		Schema::drop('categories');
	}

}
