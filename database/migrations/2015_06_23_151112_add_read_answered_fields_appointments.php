<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadAnsweredFieldsAppointments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('appointments', function ($table) {
		    $table->boolean('read')->default(false)->after('comments');
		    $table->boolean('answered')->default(false)->after('comments');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('appointments', function ($table) {
		    $table->dropColumn('read');
		    $table->dropColumn('answered');
		});
	}

}
