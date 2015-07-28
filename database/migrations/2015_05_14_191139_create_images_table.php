<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('images', function($table){
		    $table->increments('id');

		    $table->integer('listing_id')->unsigned();
		    $table->string('image_path')->unique();

		    //Foreign Keys - Relationships
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
		Schema::drop('images');
	}

}
