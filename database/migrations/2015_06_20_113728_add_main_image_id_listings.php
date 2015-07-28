<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMainImageIdListings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('listings', function ($table) {
		    $table->integer('main_image_id')->unsigned()->nullable()->after('stratum');

		    //Foreign Keys - Relationships
	        $table->foreign('main_image_id')->references('id')->on('images');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('listings', function ($table) {
			$table->dropForeign('listings_main_image_id_foreign');

		    $table->dropColumn('main_image_id');
		});
	}

}
