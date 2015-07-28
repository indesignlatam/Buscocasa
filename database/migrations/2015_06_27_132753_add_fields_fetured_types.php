<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsFeturedTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('featured_types', function ($table) {
		    $table->string('icon')->nullable();
		    $table->string('color', 10)->nullable();
		    $table->string('uk_class', 30)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('featured_types', function ($table) {
		    $table->dropColumn('icon');
		    $table->dropColumn('color');
		    $table->dropColumn('uk_class');
		});
	}

}
