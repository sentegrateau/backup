<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package__rooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id')->unsigned();
            $table->bigInteger('room_id')->unsigned();
            $table->integer('number_of_rooms');
            $table->timestamps();

            $table->foreign('package_id')
            ->references('id')
            ->on('packages');

            $table->foreign('room_id')
            ->references('id')
            ->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package__rooms');
    }
}
