<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('departments', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->integer('country_id');

		    //Foreign Keys - Relationships
	        $table->foreign('country_id')->references('id')->on('countries');
		});

		Schema::table('cities', function ($table) {
		    $table->integer('department_id')->unsigned()->nullable()->after('name');

		    //Foreign Keys - Relationships
	        $table->foreign('department_id')->references('id')->on('departments');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::table('cities', function ($table) {
			$table->dropForeign('cities_department_id_foreign');

		    $table->dropColumn('department_id');
		});

		Schema::drop('departments');
	}

}
