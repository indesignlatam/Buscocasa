<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('profiles', function($table){
		    $table->increments('id');

		    $table->integer('user_id')->unsigned()->nullable();

		    $table->string('image_path')->nullable();
		    $table->string('phone', 20)->nullable();
		    $table->string('cellphone', 25)->nullable();
		    $table->string('position')->nullable();
		    $table->string('idioms')->nullable();
		    $table->text('description')->nullable();
		    $table->string('notes')->nullable();

		    $table->timestamps();

		    //Foreign Keys - Relationships
	        $table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::drop('profiles');
	}

}
