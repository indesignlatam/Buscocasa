<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivedListingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('archived_listings', function($table){
		    $table->increments('id');

		    $table->string('slug')->unique();
		    $table->integer('broker_id')->unsigned()->nullable();// Broker
		    $table->integer('category_id')->unsigned()->nullable();// Categoria 	= Casas, Apartamentos, Lotes, Fincas
		    $table->integer('listing_type')->unsigned()->nullable();// Tipo 	= Venta, Arriendo
		    $table->integer('listing_status')->unsigned()->nullable();// Estado	= Vendido, Arrendado
		    $table->integer('city_id')->unsigned()->nullable();
		    $table->integer('district_id')->unsigned()->nullable();
		    $table->string('district')->nullable();

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
		   	$table->integer('floor')->unsigned()->nullable();
		   	$table->integer('construction_year')->unsigned()->nullable();
		   	$table->float('administration')->unsigned()->nullable();

		   	$table->enum('stratum', [1, 2 ,3 ,4 ,5 ,6])->nullable();

		    $table->timestamp('expires_at')->nullable();
		    $table->integer('featured_type')->unsigned()->nullable();
		    $table->integer('views')->unsigned()->default(0);

		    $table->timestamps();

		    //Foreign Keys - Relationships
	        $table->foreign('district_id')->references('id')->on('districts');
	        $table->foreign('broker_id')->references('id')->on('users');
	        $table->foreign('category_id')->references('id')->on('categories');
	        $table->foreign('listing_type')->references('id')->on('listing_types');
	        $table->foreign('listing_status')->references('id')->on('listing_statuses');
	        $table->foreign('city_id')->references('id')->on('cities');
	        $table->foreign('featured_type')->references('id')->on('featured_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::drop('archived_listings');
	}

}
