<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('payments', function($table){
		    $table->increments('id');

		    $table->integer('user_id')->unsigned();
		    $table->integer('listing_id')->unsigned();
		    $table->integer('featured_id')->unsigned();

		    $table->string('description');
		    $table->string('reference_code', 50);
		    $table->float('amount');
		    $table->float('tax');
		    $table->float('tax_return_base');
		    $table->string('currency', 5)->default('COP');
		    $table->string('signature');
		    $table->boolean('confirmed')->default(false);
		    $table->boolean('canceled')->default(false);

		    // PayU Data
		    $table->string('state_pol', 32)->nullable();
		    $table->decimal('risk', 2, 2)->nullable();
		    $table->string('response_code_pol')->nullable();
		    $table->string('reference_pol')->nullable();
		    $table->timestamp('transaction_date')->nullable();
		    $table->string('cus', 64)->nullable();
		    $table->string('pse_bank')->nullable();
		    $table->string('authorization_code', 12)->nullable();
		    $table->string('bank_id')->nullable();
		    $table->string('ip', 20)->nullable();
		    $table->integer('payment_method_id')->nullable();
		    $table->string('transaction_bank_id')->nullable();
		    $table->string('transaction_id', 36)->nullable();
		    $table->string('payment_method_name')->nullable();

		    $table->nullableTimestamps();

		    //Foreign Keys - Relationships
	        $table->foreign('user_id')->references('id')->on('users');
	        $table->foreign('listing_id')->references('id')->on('listings');
	        $table->foreign('featured_id')->references('id')->on('featured_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
		Schema::drop('payments');
	}

}
