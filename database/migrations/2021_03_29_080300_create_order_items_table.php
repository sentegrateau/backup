<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->on('orders')->references('id');
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')->on('packages')->references('id');
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->on('rooms')->references('id');
            $table->unsignedBigInteger('device_id');
            $table->foreign('device_id')->on('devices')->references('id');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
