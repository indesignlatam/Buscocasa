<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToListing extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('listings', function($table){
		    $table->string('code',20)->nullable()->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('listings', function($table){
		    $table->dropColumn('phone_2');
		});
	}

}
