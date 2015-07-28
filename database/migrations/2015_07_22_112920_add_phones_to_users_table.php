<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhonesToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::table('users', function($table){
		    $table->string('phone_1', 15)->nullable()->after('confirmation_code');
		    $table->string('phone_2', 15)->nullable()->after('phone_1');
		    $table->text('description')->nullable()->after('phone_2');
		    $table->string('image_path')->nullable()->after('description');
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
		    $table->dropColumn('phone_1');
		    $table->dropColumn('phone_2');
		    $table->dropColumn('description');
		    $table->dropColumn('image_path');
		});
	}

}