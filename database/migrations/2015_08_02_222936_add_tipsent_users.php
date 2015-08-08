<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipsentUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('users', function($table){
		    $table->timestamp('tips_sent_at')->nullable()->after('image_path');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('users', function($table){
		    $table->dropColumn('tips_sent_at');
		});
	}
}