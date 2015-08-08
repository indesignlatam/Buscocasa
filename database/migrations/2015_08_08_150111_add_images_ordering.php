<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImagesOrdering extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('images', function($table){
		    $table->tinyInteger('ordering')->unsigned()->nullable()->after('listing_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('images', function($table){
		    $table->dropColumn('ordering');
		});
	}

}
