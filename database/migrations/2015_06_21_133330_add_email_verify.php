<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailVerify extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('users', function ($table) {
		    $table->boolean('confirmed')->default(false)->after('remember_token');
            $table->string('confirmation_code', 64)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('users', function ($table) {
		    $table->dropColumn('confirmed');
		    $table->dropColumn('confirmation_code');
		});
	}

}