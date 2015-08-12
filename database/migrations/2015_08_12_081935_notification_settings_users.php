<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificationSettingsUsers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        //
        Schema::table('users', function($table){
            $table->boolean('email_notifications')->default(true)->after('tips_sent_at');
            $table->boolean('privacy_name')->default(true)->after('tips_sent_at');
            $table->boolean('privacy_phone')->default(true)->after('tips_sent_at');
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
            $table->dropColumn('email_notifications');
            $table->dropColumn('privacy_phone');
            $table->dropColumn('privacy_name');
        });
    }
}
