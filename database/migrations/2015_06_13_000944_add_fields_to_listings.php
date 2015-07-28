<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToListings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('districts', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->integer('city_id')->unsigned();

		    //Foreign Keys - Relationships
	        $table->foreign('city_id')->references('id')->on('cities');
		});

		Schema::table('listings', function ($table) {
		    $table->enum('stratum', [1, 2 ,3 ,4 ,5 ,6])->nullable()->after('administration');
		    $table->boolean('furnished')->default(false)->nullable()->after('administration');
		    $table->integer('district_id')->unsigned()->nullable()->after('city_id');

		    //Foreign Keys - Relationships
	        $table->foreign('district_id')->references('id')->on('districts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
