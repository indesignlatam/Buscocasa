<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpiresListings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('featured_types', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->text('description')->nullable();
		    $table->string('image_path');
		    $table->integer('price');
		});

		Schema::table('listings', function ($table) {
		    $table->timestamp('expires_at')->nullable()->after('featured');
		    $table->timestamp('featured_expires_at')->nullable()->after('featured');
		    $table->integer('featured_type')->unsigned()->nullable()->after('featured');

		    //Foreign Keys - Relationships
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
		Schema::table('listings', function ($table) {
			$table->dropForeign('listings_featured_type_foreign');

		    $table->dropColumn('expires_at');
		    $table->dropColumn('featured_expires_at');
		    $table->dropColumn('featured_type');
		});

		Schema::drop('featured_types');
	}

}
