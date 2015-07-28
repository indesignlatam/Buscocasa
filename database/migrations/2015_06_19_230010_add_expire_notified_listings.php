<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpireNotifiedListings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('listings', function ($table) {
		    $table->boolean('expire_notified')->default(false)->after('expires_at');
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
		    $table->dropColumn('expire_notified');
		});
	}

}
