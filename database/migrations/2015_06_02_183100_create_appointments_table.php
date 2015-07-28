<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('appointments', function($table){
		    $table->increments('id');

		    $table->integer('user_id')->unsigned()->nullable();

		    $table->string('name')->nullable();
		    $table->string('email')->nullable();
		    $table->string('phone', 15)->nullable();

		    $table->integer('listing_id')->unsigned()->nullable();

		    $table->text('comments')->nullable();

		    $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::drop('appointments');
	}

}
