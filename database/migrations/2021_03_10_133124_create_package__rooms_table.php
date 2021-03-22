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
        Schema::create('package__room__device', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id')->unsigned();
            $table->bigInteger('room_id')->unsigned();
            $table->bigInteger('device_id')->unsigned();
            $table->integer('max_qty');
            $table->integer('min_qty');
            $table->timestamps();

            $table->foreign('package_id')
            ->references('id')
            ->on('packages')
                ->onDelete('cascade');

            $table->foreign('room_id')
            ->references('id')
            ->on('rooms')
                ->onDelete('cascade');

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onDelete('cascade');
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
