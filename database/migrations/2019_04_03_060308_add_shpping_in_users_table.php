<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShppingInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('shipping_id');
            $table->renameColumn('name', 'first_name');
            $table->string('last_name', 50);
            $table->char('gender', 4);
            $table->char('phone', 4);
            $table->string('avatar', 300);
            $table->string('device_token', 500);
            $table->string('device_type', 20);
            $table->dateTime('last_login');
            $table->tinyInteger('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
